<?php

namespace App\Exports;

use App\Models\Taller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ParticipantesTallerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $taller;

    public function __construct(Taller $taller)
    {
        // Cargar participantes con su comuna y la fecha de inscripción desde la tabla pivote
        $this->taller = $taller->load(['participantes' => function ($query) {
            $query->with('comuna')->withPivot('fecha_inscripcion')->orderBy('nombre');
        }]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->taller->participantes;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            '#',
            'Tipo Documento',
            'Documento',
            'Nombre Completo',
            'Fecha Nacimiento',
            'Dirección',
            'Teléfono',
            'Email',
            'Barrio',
            'Comuna',
            'Fecha Inscripción Taller',
        ];
    }

    /**
    * @param mixed $participante
    * @return array
    */
    public function map($participante): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $participante->tipo_documento,
            $participante->documento,
            $participante->nombre,
            Carbon::parse($participante->fecha_nacimiento)->format('d/m/Y'),
            $participante->direccion,
            $participante->telefono,
            $participante->email,
            $participante->barrio,
            $participante->comuna->nombre ?? 'N/A',
            $participante->pivot->fecha_inscripcion ? Carbon::parse($participante->pivot->fecha_inscripcion)->format('d/m/Y H:i:s') : 'N/A',
        ];
    }
}
