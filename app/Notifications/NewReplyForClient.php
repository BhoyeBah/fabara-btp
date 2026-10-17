<?php

namespace App\Notifications;

use App\Models\DevisRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Prévient le client qu'FABARA lui a répondu (sans recopier le contenu du message). */
class NewReplyForClient extends Notification
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
        return (new MailMessage)
            ->subject('FABARA vous a répondu – demande '.$this->devis->reference)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Vous avez un nouveau message concernant votre demande '.$this->devis->reference.'.')
            ->action('Lire le message', route('client.devis.show', $this->devis->reference))
            ->line('Vous recevez cet e-mail parce que vous suivez cette demande dans votre espace client FABARA.');
    }
}
