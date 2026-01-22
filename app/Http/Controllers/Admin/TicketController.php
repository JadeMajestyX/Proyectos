<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AsignarActividad;
use App\Mail\CambioActividad;
use App\Mail\CambioStatusActividad;
use App\Models\Project;
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

    public function index(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);

        $tab = $request->query('tab', 'open');
        $allowedTabs = ['unassigned', 'assigned', 'done', 'in_progress', 'open'];
        if (!in_array($tab, $allowedTabs, true)) {
            $tab = 'open';
        }

        $query = Ticket::with(['project', 'creator', 'assignee']);

        if ($tab === 'unassigned') {
            $query->whereNull('assigned_to');
        } elseif ($tab === 'assigned') {
            $query->whereNotNull('assigned_to');
        } elseif (in_array($tab, ['done', 'in_progress', 'open'], true)) {
            $query->where('status', $tab);
        }

        $tickets = $query
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        $counts = [
            'unassigned' => Ticket::whereNull('assigned_to')->count(),
            'assigned' => Ticket::whereNotNull('assigned_to')->count(),
            'done' => Ticket::where('status', 'done')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'open' => Ticket::where('status', 'open')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'tab', 'counts'));
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


        $oldUser = $ticket->assigned_to;
        $statusOld = $ticket->status;

        
        $ticket->update($validated);

        $userNew = $ticket->assigned_to;
        $statusNew = $ticket->status;
        
        //Fuera del if para usarse en 2 casos y evitar doble consulta a la base de datos
        $user = User::findOrFail($userNew);
        $ticket->load('project');

        if($userNew && $oldUser != $userNew){
                Mail::to($user->email)
                ->send(new AsignarActividad(
                    $user,
                    $ticket
                ));
                if($oldUser){
                    $oldUserData = User::findOrFail($oldUser);
                    Mail::to($oldUserData->email)
                    ->send(new CambioActividad(
                        $oldUserData,
                        $ticket,
                        $user
                    ));
                }
            
        }
        if($statusNew != $statusOld){
            Mail::to($user->email)
            ->send(new CambioStatusActividad(
                $user,
                $ticket,
                $statusOld
            ));
        }
        

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
