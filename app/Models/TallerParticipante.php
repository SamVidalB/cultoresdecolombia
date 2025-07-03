<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TallerParticipante extends Model
{
    use HasFactory;

    protected $table = 'taller_participantes';

    protected $fillable = [
        'taller_id',
        'participante_id',
        'fecha_inscripcion',
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
