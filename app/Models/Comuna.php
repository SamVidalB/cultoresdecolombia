<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comuna extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Relaciones
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function talleres()
    {
        return $this->hasMany(Taller::class);
    }

    public function participantes()
    {
        return $this->hasMany(Participante::class);
    }
}
