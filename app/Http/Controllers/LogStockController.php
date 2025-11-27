<?php

namespace App\Http\Controllers;

use App\Models\LogStock;
use Illuminate\Http\Request;

class LogStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = LogStock::all();

        return view('stocks.index', compact('stocks'));
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
    public function show(LogStock $logStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogStock $logStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogStock $logStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogStock $logStock)
    {
        //
    }
}
