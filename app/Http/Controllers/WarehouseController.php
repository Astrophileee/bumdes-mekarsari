<?php

namespace App\Http\Controllers;

use App\Models\LogStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::all();

        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'current_stock' => 'required|integer',
            'notes' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $initialStock = $warehouse->current_stock;
            $finalStock   = $validated['current_stock'];
            $changeAmount = $finalStock - $initialStock;

            $warehouse->update([
                'current_stock' => $validated['current_stock']
            ]);

            LogStock::create([
                'type'          => 'Perubahan Manual Dari Gudang',
                'initial_stock' => $initialStock,
                'change_amount' => $changeAmount,
                'final_stock'   => $finalStock,
                'notes'         => 'Perubahan Manual Alasan: ' . $validated['notes'],
            ]);

            DB::commit();

            return redirect()->route('warehouses.index')->with('success', 'Gudang Beras berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal merubah data Gudang Beras.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        //
    }
}
