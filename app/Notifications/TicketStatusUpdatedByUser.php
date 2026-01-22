<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusUpdatedByUser extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public User $changedBy,
        public string $oldStatus,
        public string $newStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $projectName = $this->ticket->project?->name ?? '—';
        $ticketTitle = $this->ticket->title;

        return (new MailMessage)
            ->subject('Ticket actualizado por usuario')
            ->greeting('Hola')
            ->line("El usuario {$this->changedBy->name} marcó un ticket.")
            ->line("Proyecto: {$projectName}")
            ->line("Ticket: {$ticketTitle}")
            ->line('Estado anterior: '.$this->humanStatus($this->oldStatus))
            ->line('Nuevo estado: '.$this->humanStatus($this->newStatus))
            ->action('Ver ticket (admin)', route('admin.tickets.show', $this->ticket))
            ->line('Este mensaje se envió automáticamente.');
    }

    private function humanStatus(string $status): string
    {
        return match ($status) {
            'open' => 'Abierto',
            'in_progress' => 'En progreso',
            'done' => 'Completado',
            default => $status,
        };
    }
}
