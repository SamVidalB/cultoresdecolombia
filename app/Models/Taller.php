<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cultor_id',
        'comuna_id',
    ];

    // Relaciones
    public function cultor()
    {
        return $this->belongsTo(User::class, 'cultor_id');
    }

    public function comuna()
    {
        return $this->belongsTo(Comuna::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'taller_participantes')
                    ->withTimestamps()
                    ->withPivot('fecha_inscripcion');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
