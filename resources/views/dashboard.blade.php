@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        </div>
    </div>

    @php
        $cards = [
            [
                'label' => 'Menunggu Approval',
                'value' => $waitingApprovalCount,
                'icon' => 'fa-clipboard-list',
                'bg' => 'bg-orange-100',
                'text' => 'text-orange-600',
                'note' => 'Bukti bayar menunggu diproses',
            ],
            [
                'label' => 'Belum Upload Bukti',
                'value' => $pendingPaymentCount,
                'icon' => 'fa-hourglass-half',
                'bg' => 'bg-amber-100',
                'text' => 'text-amber-600',
                'note' => 'Customer belum upload bukti bayar',
            ],
            [
                'label' => 'Pembayaran Disetujui',
                'value' => $approvedPaymentCount,
                'icon' => 'fa-circle-check',
                'bg' => 'bg-green-100',
                'text' => 'text-green-600',
                'note' => 'Siap lanjut ke pengiriman',
            ],
            [
                'label' => 'Pembayaran Ditolak',
                'value' => $rejectedPaymentCount,
                'icon' => 'fa-circle-xmark',
                'bg' => 'bg-red-100',
                'text' => 'text-red-600',
                'note' => 'Perlu upload ulang bukti bayar',
            ],
            [
                'label' => 'Pesanan Aktif',
                'value' => $activeOrderCount,
                'icon' => 'fa-truck-fast',
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-600',
                'note' => 'Status pending atau delivery',
            ],
            [
                'label' => 'Pesanan Selesai',
                'value' => $completedOrderCount,
                'icon' => 'fa-flag-checkered',
                'bg' => 'bg-emerald-100',
                'text' => 'text-emerald-600',
                'note' => 'Order sudah diterima',
            ],
            [
                'label' => 'Total Produk',
                'value' => $productCount,
                'icon' => 'fa-box-archive',
                'bg' => 'bg-indigo-100',
                'text' => 'text-indigo-600',
                'note' => number_format($customerCount) . ' customer terdaftar',
            ],
            [
                'label' => 'Stok Gudang (kg)',
                'value' => $currentStock,
                'icon' => 'fa-warehouse',
                'bg' => 'bg-teal-100',
                'text' => 'text-teal-600',
                'note' => 'Total stok di gudang utama',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach ($cards as $card)
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-lg transition duration-150">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($card['value']) }}</p>
                        @if (!empty($card['note']))
                            <p class="text-xs text-gray-500 mt-1">{{ $card['note'] }}</p>
                        @endif
                    </div>
                    <div
                        class="w-12 h-12 rounded-full {{ $card['bg'] }} flex items-center justify-center {{ $card['text'] }} text-xl">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
