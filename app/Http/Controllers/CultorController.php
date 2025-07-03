<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Comuna;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class CultorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cultores = User::where('rol', 'cultor')
                      ->with('comuna')
                      ->orderBy('nombres')->get();

        $comunas = Comuna::all();


        return view('cultores.index', compact('cultores', 'comunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comunas = Comuna::all();
        return view('cultores.create', compact('comunas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string|max:20',
            'documento' => 'required|numeric|unique:users,documento',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'comuna_id' => 'required|exists:comunas,id',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rut' => 'nullable|string|max:100',
            'banco' => 'nullable|string|max:100',
            'tipo_cuenta' => 'nullable|string|max:100',
            'numero_cuenta' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::create([
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'comuna_id' => $request->comuna_id,
            'password' => Hash::make($request->password),
            'rol' => 'cultor',
            'rut' => $request->rut,
            'banco' => $request->banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
        ]);

        if ($request->hasFile('foto')) {
            $user->foto = $request->file('foto')->store('cultores/fotos', 'public');
            $user->save();
        }

        return redirect()->route('cultores.index')
                        ->with('success', 'Cultor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($cultor)
    {
        $cultor = User::findOrFail($cultor);
        
        // Asegurar que solo se vean cultores
        if ($cultor->rol != 'cultor') {
            abort(404);
        }

        $cultor->load('comuna', 'talleres.horarios');
        return view('cultores.show', compact('cultor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $cultor)
    {
        // Asegurar que solo se editen cultores
        if ($cultor->rol != 'cultor') {
            abort(404);
        }

        $comunas = Comuna::all();
        return view('cultores.edit', compact('cultor', 'comunas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $cultor)
    {
        // Asegurar que solo se actualicen cultores
        if ($cultor->rol != 'cultor') {
            abort(404);
        }

        $request->validate([
            'tipo_documento' => 'required|string|max:20',
            'documento' => 'required|numeric|unique:users,documento,'.$cultor->id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$cultor->id,
            'comuna_id' => 'required|exists:comunas,id',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'rut' => 'nullable|string|max:100',
            'banco' => 'nullable|string|max:100',
            'tipo_cuenta' => 'nullable|string|max:100',
            'numero_cuenta' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'comuna_id' => $request->comuna_id,
            'rut' => $request->rut,
            'banco' => $request->banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($cultor->foto) {
                Storage::disk('public')->delete($cultor->foto);
            }
            $data['foto'] = $request->file('foto')->store('cultores/fotos', 'public');
        }

        $cultor->update($data);

        return redirect()->route('cultores.index')
                        ->with('success', 'Cultor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cultor)
    {
        $cultor = User::findOrFail($cultor);

        // Asegurar que solo se eliminen cultores
        if ($cultor->rol != 'cultor') {
            abort(404);
        }

        // Verificar si el cultor tiene talleres asignados
        if ($cultor->talleres()->exists()) {
            return redirect()->route('cultores.index')
                           ->with('error', 'No se puede eliminar el cultor porque tiene talleres asignados.');
        }

        try {
            // Eliminar foto si existe
            if ($cultor->foto) {
                Storage::disk('public')->delete($cultor->foto);
            }

            $cultor->delete();
            return redirect()->route('cultores.index')
                           ->with('success', 'Cultor eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cultores.index')
                           ->with('error', 'Error al eliminar el cultor: '.$e->getMessage());
        }
    }
}