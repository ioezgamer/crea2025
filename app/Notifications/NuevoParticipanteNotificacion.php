<?php

namespace App\Notifications;

use App\Models\Participante;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoParticipanteNotificacion extends Notification implements ShouldQueue
{
    use Queueable;

    public Participante $participante;
    public User $actor; // El usuario que creó el participante

    /**
     * Create a new notification instance.
     */
    public function __construct(Participante $participante, User $actor)
    {
        $this->participante = $participante;
        $this->actor = $actor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Almacenará la notificación en la base de datos
    }

    /**
     * Get the array representation of the notification.
     * Esto es lo que se guardará en la columna 'data' de la tabla 'notifications'.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Generamos un array con los datos que queremos mostrar en la notificación
        return [
            'participante_id' => $this->participante->participante_id,
            'participante_nombre' => $this->participante->primer_nombre_p . ' ' . $this->participante->primer_apellido_p,
            'actor_id' => $this->actor->id,
            'actor_nombre' => $this->actor->name,
            'mensaje' => "{$this->actor->name} ha registrado al participante: {$this->participante->primer_nombre_p} {$this->participante->primer_apellido_p}.",
            'url' => route('participante.show', $this->participante->participante_id), // URL para ver el detalle del participante
            'icono' => 'user-plus', // Un icono de ejemplo para la UI
        ];
    }
}
