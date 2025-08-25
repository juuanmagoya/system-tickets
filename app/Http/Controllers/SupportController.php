<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use Inertia\Inertia;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supports = Support::paginate(10);
        return Inertia::render('Supports/Index', [
            'supports' => $supports
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Supports/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'email' => 'required|email|unique:technical_supports,email',
                'phone' => 'nullable|string|max:20',
                'speciality' => 'nullable|string|in:Software,Hardware,Network,Operating System',
            ]);
            Support::create($request->all());
            return redirect()->
                    route('supports.index')->
                    with('success', 'Registro creado Exitosamente.');

        } catch (\Exception $e) {
            return redirect()->
            back()->
            with(['error' => 'Falla al crear el registro: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Support $support)
    {
        return Inertia::render('Supports/Edit', [
            'support' => $support
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'email' => 'required|email|unique:supports,email,' . $support->id,
                'phone' => 'nullable|string|max:20',
                'speciality' => 'nullable|string|in:Software,Hardware,Network,Operating System',
            ]);
            $support->update($request->all());
            return redirect()->
                    route('supports.index')->
                    with('success', 'Registro actualizado Exitosamente.');

        } catch (\Exception $e) {
            return redirect()->
            back()->
            with(['error' => 'Falla al actualizar el registro: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Support $support)
    {
        try {
            if(!$request->has('confirm') && $support->tickets()->count() > 0){
                return redirect()->
                back()->
                with(['warning' => 'El cliente tiene ' . $support->tickets()->count() . ' tickets asociados.']);
            }
            $support->delete();
            return redirect()->
                    route('supports.index')->
                    with('success', 'Registro y tickets eliminado Exitosamente.');
        } catch (\Exception $e) {
            return redirect()->
            back()->
            with(['error' => 'Falla al eliminar el registro: ' . $e->getMessage()]);
        }
    }
}
