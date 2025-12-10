<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('user')->get();

        return view('customers.index', compact('customers'));
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
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'phone_number' => 'required|string|max:20|phone:ID',
            'password' => 'required|confirmed|min:6',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole('customer');

            Customer::create([
                'user_id' => $user->id,
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'] ?? null,
            ]);

            DB::commit();

            if (Auth::check()) {
                return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
            }

            return redirect()->route('login')->with('status', 'Registrasi berhasil!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal menyimpan data customer.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($customer->user_id)],
            'phone_number' => 'required|string|max:20|phone:ID',
            'password' => 'nullable|confirmed|min:6',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $customer->user->update($userData);

            $customer->update([
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal menyimpan data customer.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            DB::transaction(function () use ($customer) {
                $user = $customer->user;

                $customer->delete();

                if ($user) {
                    $user->delete();
                }
            });

            return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('customers.index')->with('error', 'Customer tidak dapat dihapus karena masih digunakan di data/transaksi lain.');
        }
    }
}
