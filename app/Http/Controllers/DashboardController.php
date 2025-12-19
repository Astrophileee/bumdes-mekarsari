<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        $waitingApprovalCount = Transaction::where('payment_status', 'waiting')->count();
        $pendingPaymentCount  = Transaction::where('payment_status', 'pending')->count();
        $approvedPaymentCount = Transaction::where('payment_status', 'paid')->count();
        $rejectedPaymentCount = Transaction::where('payment_status', 'reject')->count();

        $activeOrderCount    = Transaction::whereIn('status', ['pending', 'delivery'])->count();
        $completedOrderCount = Transaction::where('status', 'completed')->count();

        $productCount  = Product::count();
        $customerCount = Customer::count();

        $currentStock = Warehouse::first()?->current_stock ?? 0;

        return view('dashboard', [
            'waitingApprovalCount' => $waitingApprovalCount,
            'pendingPaymentCount'  => $pendingPaymentCount,
            'approvedPaymentCount' => $approvedPaymentCount,
            'rejectedPaymentCount' => $rejectedPaymentCount,
            'activeOrderCount'     => $activeOrderCount,
            'completedOrderCount'  => $completedOrderCount,
            'productCount'         => $productCount,
            'customerCount'        => $customerCount,
            'currentStock'         => $currentStock,
        ]);
    }
}
