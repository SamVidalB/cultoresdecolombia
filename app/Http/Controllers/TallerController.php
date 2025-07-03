<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use App\Models\User;  // Para cultores
use App\Models\Comuna; // Para comunas
use App\Models\Participante; // Para gestionar participantes
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Importar la clase QrCode
use Illuminate\Support\Str; // Importar Str facade

class TallerController extends Controller
{
    // Mostrar listado de talleres
    public function index()
    {
        $talleres = Taller::with(['cultor', 'comuna', 'participantes'])->get();
        $qrCodes = [];

        foreach ($talleres as $taller) {
            $urlRegistro = route('registro.taller.form', $taller->id);
            // Generar QR como string SVG. Se puede usar ->generate() para salida directa o ->format('png')->generate() para PNG.
            $qrCodes[$taller->id] = QrCode::size(120)->generate($urlRegistro);
        }

        // Obtener los cultores que no están asociados a ningún taller
        $cultores = User::where('rol', 'cultor')
                        ->whereDoesntHave('talleres')
                        ->get();

        $comunas = Comuna::all();

        return view('talleres.index', compact('talleres', 'cultores', 'comunas', 'qrCodes'));
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


    /**
     * Muestra la lista de participantes inscritos en un taller.
     *
     * @param  \App\Models\Taller  $taller
     * @return \Illuminate\View\View
     */
    public function participantesInscritos(Taller $taller)
    {
        // Cargar participantes con su comuna y la fecha de inscripción desde la tabla pivote
        $taller->load(['participantes' => function ($query) {
            $query->with('comuna')->withPivot('fecha_inscripcion')->orderBy('nombre');
        }]);

        return view('talleres.participantes-inscritos', compact('taller'));
    }

    /**
     * Exporta la lista de participantes de un taller a Excel.
     *
     * @param  \App\Models\Taller  $taller
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportParticipantesExcel(Taller $taller)
    {
        $fileName = 'participantes_taller_' . Str::slug($taller->nombre) . '_' . now()->format('YmdHis') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ParticipantesTallerExport($taller), $fileName);
    }

    /**
     * Exporta la lista de participantes de un taller a PDF.
     *
     * @param  \App\Models\Taller  $taller
     * @return \Illuminate\Http\Response
     */
    public function exportParticipantesPdf(Taller $taller)
    {
        // Cargar participantes con su comuna y la fecha de inscripción
        $taller->load(['participantes' => function ($query) {
            $query->with('comuna')->withPivot('fecha_inscripcion')->orderBy('nombre');
        }]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('talleres.participantes-pdf', compact('taller'));
        $fileName = 'participantes_taller_' . Str::slug($taller->nombre) . '_' . now()->format('YmdHis') . '.pdf';
        return $pdf->download($fileName);
    }
}
