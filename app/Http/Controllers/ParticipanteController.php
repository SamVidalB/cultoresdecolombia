<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


use App\Models\Taller;
use App\Models\User;
use App\Models\Comuna;
use App\Models\Participante;

use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    // Mostrar listado de talleres
    public function index()
    {
        $participantes = Participante::with(['comuna'])->get();
        $comunas = Comuna::all();

        return view('participantes.index', compact('participantes', 'comunas'));
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
        try {
            // Validación de datos
            $validated = $request->validate([
                'tipo_documento' => [
                    'required',
                    Rule::in(['CC', 'TI', 'CE', 'PA'])
                ],
                'documento' => [
                    'required',
                    'numeric',
                    'unique:participantes,documento',
                    'digits_between:6,12'
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[\pL\s\-]+$/u' // Solo letras y espacios
                ],
                'fecha_nacimiento' => [
                    'required',
                    'date',
                    // 'before:-12 years' // Mínimo 12 años de edad
                ],
                'direccion' => 'required|string|max:255',
                'telefono' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^[0-9\-\+\(\)\s]+$/' // Validación para teléfonos
                ],
                'email' => [
                    // 'nullable',
                    'email',
                    'max:255',
                    'unique:participantes,email'
                ],
                'barrio' => 'required|string|max:100',
                'comuna_id' => [
                    'required',
                    'exists:comunas,id'
                ],
                'estado' => [
                    'required',
                    Rule::in(['activo', 'inactivo'])
                ]
            ]);

            // Iniciar transacción
            DB::beginTransaction();

            // Crear el participante
            $participante = Participante::create([
                'tipo_documento' => $validated['tipo_documento'],
                'documento' => $validated['documento'],
                'nombre' => $validated['nombre'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'] ?? null,
                'barrio' => $validated['barrio'],
                'comuna_id' => $validated['comuna_id'],
                'estado' => $validated['estado']
            ]);

            DB::commit();

            return redirect()
                ->route('participantes.index')
                ->with('success', 'Participante registrado exitosamente.');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Por favor corrige los errores en el formulario.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear participante: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar el participante. Por favor inténtalo nuevamente.');
        }
    }



    // Mostrar los detalles de un taller
    public function show(Taller $taller)
    {
        $taller->load(['cultor', 'comuna', 'participantes']);
        return view('talleres.show', compact('taller'));
    }

    // Mostrar formulario para editar un taller
    public function edit(Participante $participante)
    {
        $comunas = Comuna::all();
        return view('participantes.edit', compact('participante', 'comunas'));
    }

    // Actualizar un taller existente
    public function update(Request $request, Participante $participante)
    {
        try {
            // Validación de datos
            $validated = $request->validate([
                'tipo_documento' => [
                    'required',
                    Rule::in(['CC', 'TI', 'CE', 'PA', 'RC', 'NUIP'])
                ],
                'documento' => [
                    'required',
                    'numeric',
                    'digits_between:6,12',
                    Rule::unique('participantes', 'documento')->ignore($participante->id)
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[\pL\s\-]+$/u' // Solo letras y espacios
                ],
                'fecha_nacimiento' => [
                    'required',
                    'date',
                    // 'before:-15 years' // Mínimo 15 años de edad
                ],
                'direccion' => 'required|string|max:255',
                'telefono' => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^[0-9\-\+\(\)\s]+$/' // Validación para teléfonos
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('participantes', 'email')->ignore($participante->id)
                ],
                'barrio' => 'required|string|max:100',
                'comuna_id' => [
                    'required',
                    'exists:comunas,id'
                ],
                'estado' => [
                    'required',
                    Rule::in(['activo', 'inactivo'])
                ]
            ]);

            // Iniciar transacción
            DB::beginTransaction();

            // Actualizar el participante
            $participante->update([
                'tipo_documento' => $validated['tipo_documento'],
                'documento' => $validated['documento'],
                'nombre' => $validated['nombre'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'direccion' => $validated['direccion'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'] ?? null,
                'barrio' => $validated['barrio'],
                'comuna_id' => $validated['comuna_id'],
                'estado' => $validated['estado']
            ]);

            DB::commit();

            return redirect()
                ->route('participantes.index')
                ->with('success', 'Participante actualizado exitosamente.');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Por favor corrige los errores en el formulario.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar participante: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el participante. Por favor inténtalo nuevamente.');
        }
    }

    // Eliminar un taller
    public function destroy(Participante $participante)
    {
        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Eliminar el participante
            $participante->delete();

            DB::commit();

            return redirect()
                ->route('participantes.index')
                ->with('success', 'Participante eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar participante: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Ocurrió un error al eliminar el participante. Por favor inténtalo nuevamente.');
        }
    }




}
