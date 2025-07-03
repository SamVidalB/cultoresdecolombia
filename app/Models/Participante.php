<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Participante extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'documento',
        'nombre',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'email',
        'barrio',
        'comuna_id',
        'estado',
    ];

    // Relaciones
    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    public function talleres()
    {
        return $this->belongsToMany(Taller::class, 'taller_participantes')
                    ->withTimestamps()
                    ->withPivot('fecha_inscripcion');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
