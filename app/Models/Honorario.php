<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Honorario extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultor_id',
        'mes',
        'anio',
        'adjunto_cuenta_cobro',
        'adjunto_informe',
        'adjunto_seguridad_social',
    ];

    // Relaciones
    public function cultor()
    {
        return $this->belongsTo(User::class, 'cultor_id');
    }
}
