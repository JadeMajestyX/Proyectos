<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Todos los proyectos creados por admin
        $projects = Project::whereHas('owner', fn($q) => $q->where('is_admin', true))
            ->latest()->paginate(10);
        return view('user.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // Solo visibles si el proyecto es de admin
        abort_unless($project->owner && $project->owner->is_admin, 404);
        $project->load(['owner']);

        $tickets = $project->tickets()
            ->with(['creator','assignee'])
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->get();

        $myAssigned = $tickets->where('assigned_to', auth()->id());

        return view('user.projects.show', [
            'project' => $project,
            'tickets' => $tickets,
            'myAssigned' => $myAssigned,
        ]);
    }
}
