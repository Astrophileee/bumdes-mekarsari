<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('customer.user','product')->get();

        return view('transactions.index', compact('transactions'));
    }

    public function indexApproval()
    {
        $transactions = Transaction::with('customer.user', 'product')
            ->where('payment_status', 'waiting')
            ->get();

        return view('approvals.index', compact('transactions'));
    }

    public function updateApproval(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,reject',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah.')->withInput();
        }

        $transaction->update([
            'payment_status' => $request->status,
        ]);

        return redirect()->route('approvals.index')->with('success', 'Payment Status berhasil diperbarui.');

    }

    public function history()
    {
        $customerId = Auth::user()->customer->id;

        $transactions = Transaction::with('product')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('historyTransaction', compact('transactions'));
    }

    public function uploadPaymentProof(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048'
        ]);

        if ($transaction->payment_status === 'reject' && $transaction->payment_proof) {
            $oldPath = storage_path('app/public/' . $transaction->payment_proof);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $randomName = 'bukti_pembayaran_' . uniqid() . '.' . $request->file('payment_proof')->getClientOriginalExtension();
        $photoPath = $request->file('payment_proof')->storeAs('buktiPembayaran', $randomName, 'public');

        $transaction->update([
            'payment_proof' => $photoPath,
            'payment_status' => 'waiting',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
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

        $lastCode = Transaction::latest('transaction_number')->first()?->transaction_number ?? 'TRANSACTION_00000';
        $nextNumber = (int) substr($lastCode, 12) + 1;
        $newCode = 'TRANSACTION_' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'customer_id' => 'required|exists:customers,id',
        'quantity' => 'required|integer|min:1',
        'total_price' => 'required|string',
        ]);

        $cleanTotalPrice = preg_replace('/[^0-9]/', '', $request->total_price);

        $product = Product::findOrFail($request->product_id);
        DB::beginTransaction();

        try {

            $transaction = Transaction::create([
                'transaction_number' => $newCode,
                'customer_id' => $request->customer_id,
                'product_id' => $request->product_id,
                'price_product' => $product->price,
                'qty' => $request->quantity,
                'total_price' => $cleanTotalPrice,
                'payment_proof' => null,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Product berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);

            return back()->withErrors(['error' => 'Gagal menyimpan data product.'])->withInput();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:delivery,completed,cancelled',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'Password salah.')->withInput();
        }

        $transaction->update([
            'status' => $request->status,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
