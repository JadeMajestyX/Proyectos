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
        return (new MailMessage)
            ->subject('Ticket actualizado por usuario')
            ->view('emails.admin_ticket_status_updated', [
                'ticket' => $this->ticket,
                'adminName' => $notifiable->name ?? 'Admin',
                'changedByName' => $this->changedBy->name,
                'oldStatusLabel' => $this->humanStatus($this->oldStatus),
                'newStatusLabel' => $this->humanStatus($this->newStatus),
            ]);
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
