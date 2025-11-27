@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Riwayat Transaksi</h1>
                @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="warehousesTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pembeli</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">QTY</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($transactions as $trx)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $trx->transaction_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $trx->customer->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $trx->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($trx->total_price, 0, ',', '.')}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            <span class="
                            px-3 py-1 rounded text-white
                            @if($trx->status === 'pending') bg-yellow-500
                            @elseif($trx->status === 'completed') bg-green-600
                            @elseif($trx->status === 'cancelled') bg-red-600
                            @elseif($trx->status === 'delivery' ) bg-orange-600
                            @endif
                            ">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            <span class="
                            px-3 py-1 rounded text-white
                            @if($trx->payment_status === 'pending') bg-yellow-500
                            @elseif($trx->payment_status === 'paid') bg-green-600
                            @elseif($trx->payment_status === 'reject') bg-red-600
                            @elseif($trx->payment_status === 'waiting' ) bg-orange-600
                            @endif
                            ">
                                {{ ucfirst($trx->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $trx->created_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($trx->status == 'completed' || $trx->status == 'cancelled')
                            Transaksi Selesai

                        @elseif ($trx->payment_status == 'pending')
                            Menunggu Pembayaran

                        @elseif ($trx->payment_status == 'waiting')
                            Menunggu Konfirmasi

                        @elseif (
                            $trx->payment_status == 'reject' ||
                            $trx->payment_status == 'paid' ||
                            $trx->status == 'pending' ||
                            $trx->status == 'delivery'
                        )
                            <div class="flex items-center justify-end">
                                <button
                                    type="button"
                                    class="text-blue-600 hover:text-blue-900 border border-blue-600 rounded-md px-4 py-2 text-sm"
                                    onclick='openEditModal(@json($trx))'>
                                    Edit Status
                                </button>
                            </div>
                        @endif

                        </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>



<!-- Modal Edit -->
<div id="modal-edit-status" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-status').classList.add('hidden')"
                class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Update Status Pembayaran</h2>

            <form id="editStatusForm" method="POST" action="{{ route('transactions.update', ['transaction' => '__ID__']) }}">
                @csrf
                @method('PATCH')

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="editStatus" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="delivery">Pengiriman</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Cancel</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button"
                        onclick="document.getElementById('modal-edit-status').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function openEditModal(transaction) {
    const modal = document.getElementById('modal-edit-status');
    modal.classList.remove('hidden');

    const form = document.getElementById('editStatusForm');
    form.action = form.action.replace('__ID__', transaction.id);

    const statusSelect = document.getElementById('editStatus');
    statusSelect.value = '';
}



</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection
