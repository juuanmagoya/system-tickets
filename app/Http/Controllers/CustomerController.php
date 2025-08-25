<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Inertia\Inertia;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return Inertia::render('Customers/Index', [
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);
            Customer::create($request->all());
            return redirect()->
                    route('customers.index')->
                    with('success', 'Registro creado Exitosamente.');

        } catch (\Exception $e) 
        {
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
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);
            $customer->update($request->all());
            return redirect()->
                    route('customers.index')->
                    with('success', 'Registro actualizado Exitosamente.');

        } catch (\Exception $e) 
        {
            return redirect()->
            back()->
            with(['error' => 'Falla al actualizar el registro: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($request, Customer $customer)
    {
        try {
            if(!$request->has('confirm') && $customer->tickets()->count() > 0){
                return redirect()->
                back()->
                with(['warning' => 'El cliente tiene ' . $customer->tickets()->count() . ' tickets asociados.']);
            }
            $customer->delete();
            return redirect()->
                    route('customers.index')->
                    with('success', 'Registro y tickets eliminado Exitosamente.');
        } catch (\Exception $e) {
            return redirect()->
            back()->
            with(['error' => 'Falla al eliminar el registro: ' . $e->getMessage()]);
        }
    }
}
