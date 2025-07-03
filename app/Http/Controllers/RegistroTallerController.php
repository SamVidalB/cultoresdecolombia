<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Participante;
use App\Models\Taller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistroTallerController extends Controller
{
    /**
     * Muestra el formulario de registro para un taller específico.
     *
     * @param  \App\Models\Taller  $taller
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Taller $taller)
    {
        $comunas = Comuna::orderBy('nombre')->get();
        return view('talleres.registro-publico', compact('taller', 'comunas'));
    }

    /**
     * Procesa el registro de un participante a un taller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taller  $taller
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerParticipant(Request $request, Taller $taller)
    {
        $validatedData = $request->validate([
            'tipo_documento' => 'required|string|max:100',
            'documento' => 'required|numeric|digits_between:5,20',
            'nombre' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:100',
            'telefono' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'barrio' => 'required|string|max:100',
            'comuna_id' => 'required|exists:comunas,id',
        ]);

        try {
            DB::beginTransaction();

            $participante = Participante::updateOrCreate(
                [
                    'tipo_documento' => $validatedData['tipo_documento'],
                    'documento' => $validatedData['documento'],
                ],
                [
                    'nombre' => $validatedData['nombre'],
                    'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                    'direccion' => $validatedData['direccion'],
                    'telefono' => $validatedData['telefono'],
                    'email' => $validatedData['email'],
                    'barrio' => $validatedData['barrio'],
                    'comuna_id' => $validatedData['comuna_id'],
                    'estado' => 'activo', // Por defecto 'activo' al registrarse o actualizarse
                ]
            );

            // Asociar participante al taller si no está ya asociado
            if (!$taller->participantes()->where('participante_id', $participante->id)->exists()) {
                $taller->participantes()->attach($participante->id, ['fecha_inscripcion' => now()]);
            }

            DB::commit();

            return redirect()->route('registro.taller.form', $taller->id)
                             ->with('success', '¡Te has registrado exitosamente al taller! Tus datos han sido guardados.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en el registro al taller: ' . $e->getMessage());
            return redirect()->route('registro.taller.form', $taller->id)
                             ->with('error', 'Hubo un problema con tu registro. Por favor, inténtalo de nuevo. Detalles: ' . $e->getMessage())
                             ->withInput();
        }
    }
}
