<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\LogStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HarvestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $harvests = Harvest::all();

        return view('harvests.index', compact('harvests'));
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
        $validated = $request->validate([
            'date' => 'required|date',
            'weight_in' => 'required|integer',
            'quality' => 'required|string',
            'harvest_source' => 'nullable|string',
            'price_per_kg' => 'required|string',
            'total_price' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $cleanPricePerKg = preg_replace('/[^0-9]/', '', $validated['price_per_kg']);
        $cleanTotalPrice = preg_replace('/[^0-9]/', '', $validated['total_price']);

        DB::beginTransaction();

        try {

            $harvest = Harvest::create([
                'date' => $validated['date'],
                'weight_in' => $validated['weight_in'],
                'quality' => $validated['quality'],
                'harvest_source' => $validated['harvest_source'] ?? null,
                'price_per_kg' => $cleanPricePerKg,
                'total_price' => $cleanTotalPrice,
                'notes' => $validated['notes'] ?? null,
            ]);

            $warehouse = Warehouse::first();

            $initialStock = $warehouse->current_stock;
            $changeAmount = $validated['weight_in'];
            $finalStock   = $initialStock + $changeAmount;

            $warehouse->update([
                'current_stock' => $finalStock
            ]);

            LogStock::create([
                'type'          => 'Hasil Panen',
                'initial_stock' => $initialStock,
                'change_amount' => $changeAmount,
                'final_stock'   => $finalStock,
                'notes'         => 'Stok ditambahkan dari Hasil panen ID: ' . $harvest->id,
            ]);

            DB::commit();

            return redirect()->route('harvests.index')->with('success', 'Hasil Panen berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return back()->withErrors(['error' => 'Gagal menyimpan data hasil Panen.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Harvest $harvest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Harvest $harvest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Harvest $harvest)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'weight_in' => 'required|integer',
            'quality' => 'required|string',
            'harvest_source' => 'nullable|string',
            'price_per_kg' => 'required|string',
            'total_price' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $cleanPricePerKg = preg_replace('/[^0-9]/', '', $validated['price_per_kg']);
        $cleanTotalPrice = preg_replace('/[^0-9]/', '', $validated['total_price']);

        DB::beginTransaction();

        try {

            $oldWeight = $harvest->weight_in;
            $newWeight = $validated['weight_in'];
            $difference = $newWeight - $oldWeight;

            $warehouse = Warehouse::first();

            $initialStock = $warehouse->current_stock;
            $finalStock   = $initialStock + $difference;

            $warehouse->update([
                'current_stock' => $finalStock
            ]);
            if ($difference != 0) {
                LogStock::create([
                    'type'          => 'Hasil Panen Dirubah',
                    'initial_stock' => $initialStock,
                    'change_amount' => $difference,
                    'final_stock'   => $finalStock,
                    'notes'         => 'Stok berubah akibat update Hasil Panen ID: ' . $harvest->id,
                ]);
            }

            $harvest->update([
                'date' => $validated['date'],
                'weight_in' => $validated['weight_in'],
                'quality' => $validated['quality'],
                'harvest_source' => $validated['harvest_source'] ?? null,
                'price_per_kg' => $cleanPricePerKg,
                'total_price' => $cleanTotalPrice,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('harvests.index')->with('success', 'Hasil Panen berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal merubah data Hasil Panen.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Harvest $harvest)
    {
        DB::beginTransaction();

        try {

            $warehouse = Warehouse::first();
            $initialStock = $warehouse->current_stock;
            $changeAmount = -$harvest->weight_in;
            $finalStock = $initialStock + $changeAmount;
            $warehouse->update([
                'current_stock' => $finalStock
            ]);

            LogStock::create([
                'type'          => 'Hasil Panen Dihapus',
                'initial_stock' => $initialStock,
                'change_amount' => $changeAmount,
                'final_stock'   => $finalStock,
                'notes'         => 'Stok berkurang karena Hasil Panen ID: ' . $harvest->id . ' dihapus',
            ]);
            $harvest->delete();

            DB::commit();

            return redirect()
                ->route('harvests.index')
                ->with('success', 'Hasil Panen berhasil dihapus.');

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return back()->withErrors(['error' => 'Gagal menghapus data Hasil Panen.']);
        }
    }
}
