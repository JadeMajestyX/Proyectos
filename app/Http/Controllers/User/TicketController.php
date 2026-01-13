<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Project $project)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        return view('user.tickets.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'priority' => ['required','in:low,medium,high'],
            'image' => ['nullable','image','max:2048']
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create([
            'project_id' => $project->id,
            'created_by' => Auth::id(),
            'assigned_to' => null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'open',
            'priority' => $validated['priority'],
            'image_path' => $path,
        ]);

        return redirect()->route('user.projects.show', $project)->with('status', 'Ticket creado');
    }

    public function show(Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);

        $ticket->load(['project','creator','assignee']);

        return view('user.tickets.show', compact('project','ticket'));
    }
}
