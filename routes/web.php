<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\User\ProjectController as UserProjectController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.start');
})->middleware('guest')->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();

    $assigned = Ticket::with(['project','creator','assignee'])
        ->where('assigned_to', $user->id)
        ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderByDesc('created_at')
        ->limit(10)
        ->get();

    $created = Ticket::with(['project','creator','assignee'])
        ->where('created_by', $user->id)
        ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderByDesc('created_at')
        ->limit(10)
        ->get();

    return view('dashboard', compact('assigned','created'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin - Proyectos
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [AdminProjectController::class, 'store'])->name('projects.store');
});

// Usuario - Proyectos (solo ver)
Route::middleware(['auth'])->prefix('projects')->name('user.projects.')->group(function () {
    Route::get('/', [UserProjectController::class, 'index'])->name('index');
    Route::get('/{project}', [UserProjectController::class, 'show'])->name('show');

    // Tickets del usuario por proyecto
    Route::get('/{project}/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create');
    Route::post('/{project}/tickets', [UserTicketController::class, 'store'])->name('tickets.store');
    Route::get('/{project}/tickets/{ticket}', [UserTicketController::class, 'show'])->name('tickets.show');
    Route::get('/{project}/tickets/{ticket}/edit', [UserTicketController::class, 'edit'])->name('tickets.edit');
    Route::patch('/{project}/tickets/{ticket}', [UserTicketController::class, 'update'])->name('tickets.update');
    Route::delete('/{project}/tickets/{ticket}', [UserTicketController::class, 'destroy'])->name('tickets.destroy');
});

// Admin - Tickets gestión (asignar, listar)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::get('tickets/{ticket}/edit', [AdminTicketController::class, 'edit'])->name('tickets.edit');
    Route::patch('tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{ticket}', [AdminTicketController::class, 'destroy'])->name('tickets.destroy');
});
