<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultor_id',
        'asistencia',
        'fecha_asistencia',
        'evidencia1',
        'fecha_evidencia1',
        'evidencia2',
        'fecha_evidencia2',
        'evidencia3',
        'fecha_evidencia3',
    ];

    // Relaciones
    public function cultor()
    {
        return $this->belongsTo(User::class, 'cultor_id');
    }
}
