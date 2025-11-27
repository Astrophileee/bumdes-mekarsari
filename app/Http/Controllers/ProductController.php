<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function indexCustomer()
    {
        $products = Product::all();
        $warehouses = Warehouse::first();

        return view('product', compact('products','warehouses'));
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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $randomName = 'photo_product_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                $photoPath = $request->file('photo')->storeAs('products', $randomName, 'public');
            }

            $product = Product::create([
                'name' => $validated['name'],
                'code' => strtoupper(substr($validated['name'], 0, 3)) . rand(100, 999),
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'photo' => $photoPath,
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->withErrors(['error' => 'Gagal menyimpan data product.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $warehouses = Warehouse::first();

        return view('detailProduct', compact('product','warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $photoPath = null;
                $photoPath = $product->photo;
                if ($request->hasFile('photo')) {
                    if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                        Storage::disk('public')->delete($product->photo);
                    }
                    $randomName = 'photo_product_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                    $photoPath = $request->file('photo')->storeAs('products', $randomName, 'public');
                }


            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'photo' => $photoPath,
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'product berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->withErrors(['error' => 'Gagal menyimpan data product.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }

            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('products.index')->with('error', 'Product tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
