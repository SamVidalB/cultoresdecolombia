<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use App\Models\User;  // Para cultores
use App\Models\Comuna; // Para comunas
use App\Models\Participante; // Para gestionar participantes
use Illuminate\Http\Request;

class TallerController extends Controller
{
    // Mostrar listado de talleres
    public function index()
    {
        $talleres = Taller::with(['cultor', 'comuna', 'participantes'])->get();

        // Obtener los cultores que no están asociados a ningún taller
        $cultores = User::where('rol', 'cultor')
                        ->whereDoesntHave('talleres')
                        ->get();

        $comunas = Comuna::all();

        return view('talleres.index', compact('talleres', 'cultores', 'comunas'));
    }


    // Mostrar formulario para crear un nuevo taller
    public function create()
    {
        // $cultores = User::where('role', 'cultor')->get();
        // $comunas = Comuna::all();
        // return view('talleres.create', compact('cultores', 'comunas'));
    }

    // Almacenar un nuevo taller
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cultor_id' => 'nullable|exists:cultores,id',
            'comuna_id' => 'required|exists:comunas,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.dia_semana' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
            'horarios.*.lugar' => 'required|string|max:255',
        ]);

        $taller = Taller::create($request->only(['nombre', 'descripcion', 'cultor_id', 'comuna_id']));

        foreach ($request->horarios as $horario) {
            $taller->horarios()->create([
                'dia_semana' => $horario['dia_semana'],
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin'],
                'lugar' => $horario['lugar'],
            ]);
        }

        return redirect()->route('talleres.index')->with('success', 'Taller registrado con éxito.');
    }



    // Mostrar los detalles de un taller
    public function show(Taller $taller)
    {
        $taller->load(['cultor', 'comuna', 'participantes']);
        return view('talleres.show', compact('taller'));
    }

    // Mostrar formulario para editar un taller
    public function edit(Taller $taller)
    {
        $cultores = User::where('rol', 'cultor')
                ->where(function($query) use ($taller) {
                    $query->whereDoesntHave('talleres')
                          ->orWhere('id', $taller->cultor_id);
                })->get();
                        
        $comunas = Comuna::all();
        
        return view('talleres.edit', compact('taller', 'cultores', 'comunas'));
    }

    // Actualizar un taller existente
    public function update(Request $request, Taller $taller)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cultor_id' => 'nullable|exists:users,id',
            'comuna_id' => 'required|exists:comunas,id',
        ]);

        $taller->update($request->all());

        return redirect()->route('talleres.index')
                         ->with('success', 'Taller actualizado correctamente.');
    }

    // Eliminar un taller
    public function destroy(Taller $taller)
    {
        $errores = [];
        
        if ($taller->participantes()->exists()) {
            $errores[] = 'tiene participantes asociados';
        }
        
        if ($taller->horarios()->exists()) {
            $errores[] = 'tiene horarios programados';
        }
        
        if ($taller->asistencias()->exists()) {
            $errores[] = 'tiene registros de asistencia';
        }
        
        if (!empty($errores)) {
            $mensaje = 'No se puede eliminar el taller porque: ' . implode(', ', $errores) . '.';
            return redirect()->route('talleres.index')
                            ->with('error', $mensaje);
        }

        // Si no hay relaciones, proceder con la eliminación
        try {
            $taller->delete();
            return redirect()->route('talleres.index')
                            ->with('success', 'Taller eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('talleres.index')
                            ->with('error', 'Error al eliminar el taller: ' . $e->getMessage());
        }
    }

    // Asignar participantes a un taller
    public function asignarParticipantes(Request $request, Taller $taller)
    {
        $request->validate([
            'participantes' => 'required|array',
            'participantes.*' => 'exists:participantes,id', // Asegurarse de que los participantes existan
        ]);

        $taller->participantes()->attach($request->participantes, ['fecha_inscripcion' => now()]);

        return redirect()->route('talleres.show', $taller->id)
                         ->with('success', 'Participantes asignados correctamente.');
    }

    // Eliminar participantes de un taller
    public function eliminarParticipantes(Taller $taller, $participanteId)
    {
        $taller->participantes()->detach($participanteId);

        return redirect()->route('talleres.show', $taller->id)
                         ->with('success', 'Participante eliminado correctamente del taller.');
    }




}
