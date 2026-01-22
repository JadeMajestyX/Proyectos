<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Notifications\TicketStatusUpdatedByUser;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            foreach ($request->file('media') as $file) {
                $stored = $file->store('tickets', 'public_storage');
                $mime = $file->getClientMimeType();
                $type = str_starts_with($mime, 'video') ? 'video' : 'image';
                TicketMedia::create([
                    'ticket_id' => $ticket->id,
                    'path' => $stored,
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
            foreach ($request->file('media') as $file) {
                $stored = $file->store('tickets', 'public_storage');
                $mime = $file->getClientMimeType();
                $type = str_starts_with($mime, 'video') ? 'video' : 'image';
                TicketMedia::create([
                    'ticket_id' => $ticket->id,
                    'path' => $stored,
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
            Storage::disk('public_storage')->delete($m->path);
            Storage::disk('public')->delete($m->path); // fallback legacy
        }
        if ($ticket->image_path) {
            Storage::disk('public_storage')->delete($ticket->image_path);
            Storage::disk('public')->delete($ticket->image_path);
        }
        $ticket->delete();

        return redirect()->route('user.projects.show', $project)->with('status','Ticket eliminado');
    }

    public function updateStatus(Request $request, Project $project, Ticket $ticket)
    {
        abort_unless($project->owner && $project->owner->is_admin, 404);
        abort_unless($ticket->project_id === $project->id, 404);
        abort_unless($ticket->assigned_to && $ticket->assigned_to === Auth::id(), 403);

        // Una vez completado, ya no se puede volver a cambiar
        abort_if($ticket->status === 'done', 403);

        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,done'],
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $validated['status'];

        // Transiciones permitidas:
        // - open -> in_progress
        // - open/in_progress -> done
        if ($newStatus === 'in_progress' && $oldStatus !== 'open') {
            abort(403);
        }
        if ($newStatus === 'done' && !in_array($oldStatus, ['open', 'in_progress'], true)) {
            abort(403);
        }

        if ($oldStatus !== $newStatus) {
            $ticket->update([
                'status' => $newStatus,
            ]);

            $ticket->load(['project', 'creator', 'assignee']);

            if ($project->owner && $project->owner->is_admin) {
                $project->owner->notify(new TicketStatusUpdatedByUser(
                    ticket: $ticket,
                    changedBy: Auth::user(),
                    oldStatus: $oldStatus,
                    newStatus: $newStatus,
                ));
            }
        }

        return redirect()
            ->route('user.projects.tickets.show', [$project, $ticket])
            ->with('status', 'Estado del ticket actualizado');
    }
}
