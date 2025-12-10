<div id="overlay" onclick="closeSidebar()" class="fixed inset-0 bg-[rgba(0,0,0,0.75)] z-30 hidden lg:hidden"></div>



<!-- Sidebar -->
<aside id="sidebar" class="fixed z-40 top-0 left-0 w-64 min-h-screen bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto">
    <div class="p-4 flex items-center gap-2">
        <div>
            <h1 class="font-bold text-sm">BUMDES</h1>
            <p class="text-xs text-gray-500">mekarsari</p>
        </div>
    </div>
    <nav class="mt-4 space-y-2 text-sm">
    <!-- Single link -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-home w-5 h-5 pt-1 text-gray-600"></i>
        Dashboard
    </a>
    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-users w-5 h-5 pt-1 text-gray-600"></i>
        Customer
    </a>
    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-box-open w-5 h-5 pt-1 text-gray-600"></i>
        Product
    </a>
    <a href="{{ route('harvests.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-seedling w-5 h-5 pt-1 text-gray-600"></i>
        Hasil Panen Beras
    </a>
    <a href="{{ route('warehouses.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-warehouse w-5 h-5 pt-1 text-gray-600"></i>
        Gudang Beras
    </a>
    <a href="{{ route('stocks.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-history w-5 h-5 pt-1 text-gray-600"></i>
        Riwayat Stok Beras
    </a>
    <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-receipt w-5 h-5 pt-1 text-gray-600"></i>
        Transaksi
    </a>
    <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-check-circle w-5 h-5 pt-1 text-gray-600"></i>
        Approval Pembayaran
    </a>

    </nav>
</aside>
