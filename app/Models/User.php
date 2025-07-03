<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'tipo_documento',
        'documento',
        'nombres',
        'apellidos',
        'foto',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'email',
        'password',
        'rol',
        'comuna_id',
        'estado',
        'adjunto_documento',
        'adjunto_rut',
        'adjunto_banco',
        'rut',
        'banco',
        'tipo_cuenta',
        'numero_cuenta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    public function talleres()
    {
        return $this->hasMany(Taller::class, 'cultor_id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'cultor_id');
    }

    public function honorarios()
    {
        return $this->hasMany(Honorario::class, 'cultor_id');
    }
}
