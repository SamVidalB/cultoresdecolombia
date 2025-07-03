<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Taller;
use App\Models\Horario;

use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|exists:talleres,id',
            'dia_semana' => [
                'required',
                Rule::in(['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'])
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'lugar' => 'required|string|max:255'
        ]);

        try {
            $horario = Horario::create($request->all());
            
            return redirect()->back()
                           ->with('success', 'Horario agregado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al crear el horario: '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $horario)
    {
        $horario = Horario::findOrFail($horario);
        
        $request->validate([
            'dia_semana' => [
                'required',
                Rule::in(['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'])
            ],
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'lugar' => 'required|string|max:255'
        ]);

        try {
            $horario->update($request->all());
            
            return redirect()->back()
                           ->with('success', 'Horario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el horario: '.$e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($horario)
    {
        $horario = Horario::findOrFail($horario);

        try {
            $horario->delete();
            
            return redirect()->back()
                           ->with('success', 'Horario eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar el horario: '.$e->getMessage());
        }
    }

    /**
     * Obtener horarios por taller (para API)
     */
    public function porTaller(Taller $taller)
    {
        $horarios = $taller->horarios()
                         ->orderByRaw("FIELD(dia_semana, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo')")
                         ->orderBy('hora_inicio')
                         ->get();

        return response()->json($horarios);
    }

    /**
     * Validar disponibilidad de horario
     */
    public function validarDisponibilidad(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|exists:talleres,id',
            'dia_semana' => [
                'required',
                Rule::in(['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'])
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'horario_id' => 'nullable|exists:horarios,id' // Para edición
        ]);

        $taller = Taller::find($request->taller_id);
        $horarios = $taller->horarios()
                         ->where('dia_semana', $request->dia_semana)
                         ->when($request->horario_id, function($query) use ($request) {
                             return $query->where('id', '!=', $request->horario_id);
                         })
                         ->get();

        $nuevaHoraInicio = strtotime($request->hora_inicio);
        $nuevaHoraFin = strtotime($request->hora_fin);

        foreach ($horarios as $horario) {
            $horaInicioExistente = strtotime($horario->hora_inicio);
            $horaFinExistente = strtotime($horario->hora_fin);

            if (($nuevaHoraInicio >= $horaInicioExistente && $nuevaHoraInicio < $horaFinExistente) ||
                ($nuevaHoraFin > $horaInicioExistente && $nuevaHoraFin <= $horaFinExistente) ||
                ($nuevaHoraInicio <= $horaInicioExistente && $nuevaHoraFin >= $horaFinExistente)) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'El horario se superpone con otro existente: '.$horario->hora_inicio.' - '.$horario->hora_fin
                ]);
            }
        }

        return response()->json(['disponible' => true]);
    }
}