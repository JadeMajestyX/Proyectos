<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'category' => ['required','in:bug,actualizacion,novedad,mejora,otro'],
            'priority' => ['required','in:low,medium,high'],
            'media' => ['nullable','array'],
            'media.*' => ['file','mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg,mov','max:20480'] // 20MB por archivo
        ]);

        $ticket = Ticket::create([
            'project_id' => $project->id,
            'created_by' => Auth::id(),
            'assigned_to' => null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'open',
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'image_path' => null,
        ]);

        if ($request->hasFile('media')) {
            $dir = public_path('tickets');
            if (!is_dir($dir)) @mkdir($dir, 0755, true);
            foreach ($request->file('media') as $file) {
                $filename = $file->hashName();
                $file->move($dir, $filename);
                $mime = $file->getClientMimeType();
                $type = str_starts_with($mime, 'video') ? 'video' : 'image';
                TicketMedia::create([
                    'ticket_id' => $ticket->id,
                    'path' => 'tickets/'.$filename,
                    'type' => $type,
                    'mime' => $mime,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('user.projects.show', $project)->with('status', 'Ticket creado');
    }

    public function show(Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);

        $ticket->load(['project','creator','assignee','media']);

        return view('user.tickets.show', compact('project','ticket'));
    }

    public function edit(Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);
        abort_unless($ticket->created_by === Auth::id(), 403);
        return view('user.tickets.edit', compact('project','ticket'));
    }

    public function update(Request $request, Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);
        abort_unless($ticket->created_by === Auth::id(), 403);
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'category' => ['required','in:bug,actualizacion,novedad,mejora,otro'],
            'priority' => ['required','in:low,medium,high'],
            'media' => ['nullable','array'],
            'media.*' => ['file','mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg,mov','max:20480']
        ]);

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'priority' => $validated['priority'],
        ]);

        if ($request->hasFile('media')) {
            $dir = public_path('tickets');
            if (!is_dir($dir)) @mkdir($dir, 0755, true);
            foreach ($request->file('media') as $file) {
                $filename = $file->hashName();
                $file->move($dir, $filename);
                $mime = $file->getClientMimeType();
                $type = str_starts_with($mime, 'video') ? 'video' : 'image';
                TicketMedia::create([
                    'ticket_id' => $ticket->id,
                    'path' => 'tickets/'.$filename,
                    'type' => $type,
                    'mime' => $mime,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('user.projects.tickets.show', [$project, $ticket])->with('status','Ticket actualizado');
    }

    public function destroy(Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);
        abort_unless($ticket->created_by === Auth::id(), 403);

        $ticket->load('media');
        foreach ($ticket->media as $m) {
            if (Storage::disk('public')->exists($m->path)) {
                Storage::disk('public')->delete($m->path);
            }
            $pub = public_path($m->path);
            if (is_file($pub)) @unlink($pub);
        }
        if ($ticket->image_path) {
            if (Storage::disk('public')->exists($ticket->image_path)) {
                Storage::disk('public')->delete($ticket->image_path);
            }
            $pub = public_path($ticket->image_path);
            if (is_file($pub)) @unlink($pub);
        }
        $ticket->delete();

        return redirect()->route('user.projects.show', $project)->with('status','Ticket eliminado');
    }
}
