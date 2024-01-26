<?php

namespace App\Listeners;

use App\Events\ChirpCreated;
use App\Models\User;
use App\Notifications\NewChirp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChirpCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChirpCreated $event): void
    {
        //ESCUCHANDO LOS EVENTOS, Y ESTE METODO SE EJECUTA CUANDO SE CREA UN CHIRP
        // $event->chirp contiene el chirp que se acaba de crear
        // $event->chirp->user contiene el usuario que creo el chirp
        // Tenemos que enviar un correo electronico a todos los usuarios que no sean el usuario que creo el chirp
        // UTILIZA LA ESTRUCTURA DE TIPO COLA Y EL METODO ASYN (ASINCRONA) PARA MANTENER EL ORDEN DE LOS ENVIOS
        //cuando se crea un chirp, se envia un correo electronico
        foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
            $user->notify(new NewChirp($event->chirp));
            //recorre todos los usuarios que no sea el usuario que creo el chirp y les envia un correo de notificacion
            //metodo cursor(), evitamos carga de memoria
            //metodo foreach, recorre todos los usuarios, y envia un correo electronico a cada uno
        }

    }
}
