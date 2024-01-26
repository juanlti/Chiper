<?php

namespace App\Notifications;

use App\Models\Chirp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChirp extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Chirp $chirp)
    {
        // con el parametro public Chirp, vamos a tener acceso a toda la clase  de NewChirp
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //toMail realiza el envio del correo electronico
        return (new MailMessage)
            ->line('The introduction to the notification.')
            // line =>  introducion o asunto
            ->action('Notification Action', url('/'))
            // action => volvemos a la pagina principal
            ->subject("New Chirp from {$this->chirp->user->name}")
            // subject => quien realiza el chirp
            ->greeting("New Chirp from {$this->chirp->user->name}")
            ->line(Str::limit($this->chirp->message, 50))
            ->action('Go to Chirper', url('/'))
            ->line('¡Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
