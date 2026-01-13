<?php

namespace App\Http\Controllers\Admin;

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
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $projects = Project::latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user() && Auth::user()->is_admin, 403);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string']
        ]);

        Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.projects.index')->with('status', 'Proyecto creado');
    }
}
