<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comuna;
use App\Models\User;
use App\Models\Taller;


class ComunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunas = Comuna::all();
        return view('comunas.index', compact('comunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('comunas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:comunas,nombre',
        ]);

        Comuna::create($request->only('nombre'));

        return redirect()->route('comunas.index')
                         ->with('success', 'Comuna creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comuna $comuna)
    {
        return view('comunas.show', compact('comuna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comuna $comuna)
    {
        return view('comunas.edit', compact('comuna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comuna $comuna)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:comunas,nombre,' . $comuna->id,
        ]);

        $comuna->update($request->only('nombre'));

        return redirect()->route('comunas.index')
                         ->with('success', 'Comuna actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comuna $comuna)
    {
        // Verificar si la comuna tiene usuarios, talleres o participantes asociados
        if ($comuna->usuarios()->exists()) {
            return redirect()->route('comunas.index')
                            ->with('error', 'No se puede eliminar la comuna porque tiene usuarios asociados.');
        }

        if ($comuna->talleres()->exists()) {
            return redirect()->route('comunas.index')
                            ->with('error', 'No se puede eliminar la comuna porque tiene talleres asociados.');
        }

        if ($comuna->participantes()->exists()) {
            return redirect()->route('comunas.index')
                            ->with('error', 'No se puede eliminar la comuna porque tiene participantes asociados.');
        }

        // Si no hay relaciones, eliminar la comuna
        $comuna->delete();

        return redirect()->route('comunas.index')
                        ->with('success', 'Comuna eliminada correctamente.');
    }

    public function talleres($id)
    {
        $comuna = Comuna::with('talleres.cultor')->findOrFail($id);

        // Obtener los cultores que no están asociados a ningún taller en esta comuna
        $cultores = User::where('rol', 'cultor')
                        ->whereDoesntHave('talleres', function ($query) use ($id) {
                            $query->where('comuna_id', $id);
                        })
                        ->get();

        return view('comunas.talleres', compact('comuna', 'cultores'));
    }


    public function storeTaller(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cultor_id' => 'required|exists:users,id',
            'comuna_id' => 'required|exists:comunas,id',
        ]);

        Taller::create($request->all());

        return redirect()->route('comunas.talleres', ['comuna' => $request->comuna_id])
                         ->with('success', 'Taller creado correctamente.');
    }

}
