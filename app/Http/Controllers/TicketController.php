<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Support;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['customer','support'])->latest()->paginate(10);
        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::select('id','name')->get();
        $supports = Support::select('id','name')->get();

        return Inertia::render('Tickets/Create', [
            'customers' => $customers,
            'supports' => $supports
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'support_id'  => 'required|exists:supports,id',
                'description' => 'required|string',
                'status' => 'required|in:Open,In Progress,Pending',
            ]);
            Ticket::create($validated);
            return redirect()->
            route('tickets.index')->
            with('success', 'Ticket creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Falla al crear el ticket: ' . $e->getMessage()]);
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
    public function edit(Ticket $ticket)
    {
        $customers = Customer::select('id','name')->get();
        $supports = Support::select('id','name')->get();

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket->load(['customer','support']),
            'customers' => $customers,
            'supports' => $supports
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'support_id'  => 'required|exists:supports,id',
                'description' => 'required|string',
                'status' => 'required|in:Open,In Progress,Pending',
            ]);
            $ticket->update($validated);
            return redirect()->
            route('tickets.index')->
            with('success', 'Ticket actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Falla al actualizar el ticket: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            $ticket->delete();
            return redirect()->
            route('tickets.index')->
            with('success', 'Ticket eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Falla al eliminar el ticket: ' . $e->getMessage()]);
        }
    }
}
