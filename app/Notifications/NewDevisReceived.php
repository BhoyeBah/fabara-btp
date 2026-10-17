<?php

namespace App\Notifications;

use App\Models\DevisRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDevisReceived extends Notification
{
    public function __construct(public DevisRequest $devis)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $d = $this->devis;

        return (new MailMessage)
            ->subject('Nouvelle demande de devis '.$d->reference)
            ->greeting('Nouvelle demande de devis')
            ->line($d->name.' · '.$d->phone)
            ->line('Lieu : '.$d->commune.' · '.$d->property_type)
            ->line('Travaux : '.$d->worksList())
            ->action('Ouvrir la demande', route('admin.devis.show', $d));
    }
}
