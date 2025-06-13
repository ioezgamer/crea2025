<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Puedes descomentar si usas verificación de email obligatoria
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <--- AÑADE ESTA LÍNEA

class User extends Authenticatable // Opcionalmente: implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // <--- AÑADE HasRoles AQUÍ

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approved_at', // Nuevo campo para la aprobación
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime', // Castear a objeto Carbon
        ];
    }

    /**
     * Determina si el usuario ha sido aprobado.
     */
    public function isApproved(): bool
    {
        return !is_null($this->approved_at);
    }

    /**
     * Scope para obtener solo usuarios aprobados.
     */
    // En App\Models\User.php
public function scopeApproved($query)
{
    return $query->whereNotNull('approved_at');
}

public function scopePendingApproval($query)
{
    return $query->whereNull('approved_at');
}
}
