<?php

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketStatusUpdatedByUser;
use Illuminate\Support\Facades\Notification;

it('permite al usuario asignado marcar en progreso y notifica al admin del proyecto', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $assignee = User::factory()->create([
        'is_admin' => false,
    ]);

    $project = Project::create([
        'name' => 'Proyecto demo',
        'description' => 'Desc',
        'created_by' => $admin->id,
    ]);

    $ticket = Ticket::create([
        'project_id' => $project->id,
        'created_by' => $assignee->id,
        'assigned_to' => $assignee->id,
        'title' => 'Ticket demo',
        'description' => null,
        'status' => 'open',
        'category' => 'bug',
        'priority' => 'low',
        'image_path' => null,
    ]);

    $this->actingAs($assignee)
        ->patch(route('user.projects.tickets.status', [$project, $ticket]), [
            'status' => 'in_progress',
        ])
        ->assertRedirect(route('user.projects.tickets.show', [$project, $ticket]));

    expect($ticket->fresh()->status)->toBe('in_progress');

    Notification::assertSentTo(
        $admin,
        TicketStatusUpdatedByUser::class,
        function (TicketStatusUpdatedByUser $notification) use ($ticket, $assignee) {
            return $notification->ticket->id === $ticket->id
                && $notification->changedBy->id === $assignee->id
                && $notification->oldStatus === 'open'
                && $notification->newStatus === 'in_progress';
        }
    );
});

it('impide a un usuario no asignado cambiar el estado', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $assignee = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::create([
        'name' => 'Proyecto demo',
        'description' => 'Desc',
        'created_by' => $admin->id,
    ]);

    $ticket = Ticket::create([
        'project_id' => $project->id,
        'created_by' => $assignee->id,
        'assigned_to' => $assignee->id,
        'title' => 'Ticket demo',
        'description' => null,
        'status' => 'open',
        'category' => 'bug',
        'priority' => 'low',
        'image_path' => null,
    ]);

    $this->actingAs($otherUser)
        ->patch(route('user.projects.tickets.status', [$project, $ticket]), [
            'status' => 'done',
        ])
        ->assertStatus(403);

    expect($ticket->fresh()->status)->toBe('open');
});
