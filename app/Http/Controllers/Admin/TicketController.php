<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AsignarActividad;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $tickets = Ticket::with(['project','creator','assignee'])
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $users = User::orderBy('name')->get(['id','name']);
        return view('admin.tickets.edit', compact('ticket','users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $validated = $request->validate([
            'assigned_to' => ['nullable','exists:users,id'],
            'status' => ['required','in:open,in_progress,done'],
            'priority' => ['required','in:low,medium,high'],
            'category' => ['required','in:bug,actualizacion,novedad,mejora,otro']
        ]);

        if(!$request->assigned_to){
            return;
        }else{
            if($ticket->assigned_to =! $request->assigned_to){
                $user = User::findOrFail($request->assigned_to);
                Mail::to($user->email)
                ->send(new AsignarActividad(
                    $user,
                    $request
                ));
            }

        }

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('status','Ticket actualizado');
    }

    public function show(Ticket $ticket)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $ticket->load(['project','creator','assignee','media']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function destroy(Ticket $ticket)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
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
        return redirect()->route('admin.tickets.index')->with('status','Ticket eliminado');
    }
}
