<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'taller_id',
        'participante_id',
        'fecha',
    ];

    // Relaciones
    public function taller()
    {
        return $this->belongsTo(Taller::class);
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}
