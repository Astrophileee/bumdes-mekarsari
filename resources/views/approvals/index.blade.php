@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Approval Pembayaran</h1>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bukti Pembayaran</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($trx->total_price, 0, ',', '.')}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            @if($trx->payment_proof)
                                <img
                                    src="{{ asset('storage/'.$trx->payment_proof) }}"
                                    alt="Bukti Pembayaran"
                                    class="w-16 h-16 object-cover cursor-pointer rounded"
                                    onclick="openImageModal(this.src)">
                            @else
                                Belum Mengirimkan Bukti
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            <span class="px-3 py-1 rounded text-white bg-orange-600">
                                {{ ucfirst($trx->payment_status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $trx->created_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if ($trx->payment_proof != null)
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Tombol Edit -->
                                <button
                                type="button"
                                class="text-blue-600 hover:text-blue-900 border border-blue-600 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200"
                                onclick='openEditModal(@json($trx))'>
                                Edit Status Pembayaran
                                </button>
                            </div>
                            @else
                            -
                            @endif
                        </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <!-- Modal Zoom Foto -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
    <span class="absolute top-4 right-8 text-white text-3xl cursor-pointer" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" class="max-h-[90vh] max-w-[90vw] rounded shadow-lg">
</div>

<!-- Modal Edit -->
<div id="modal-edit-approval" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-approval').classList.add('hidden')"
                class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Approval Status Pembayaran</h2>

            <form id="editApprovalForm" method="POST" action="{{ route('approvals.update', ['transaction' => '__ID__']) }}">
                @csrf
                @method('PATCH')

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="editStatus" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="paid">Diterima</option>
                        <option value="reject">Ditolak</option>
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
                        onclick="document.getElementById('modal-edit-approval').classList.add('hidden')"
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
    const modal = document.getElementById('modal-edit-approval');
    modal.classList.remove('hidden');

    const form = document.getElementById('editApprovalForm');
    form.action = form.action.replace('__ID__', transaction.id);

    const statusSelect = document.getElementById('editStatus');
    statusSelect.value = '';
}


function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modalImg.src = src;
    modal.classList.remove('hidden');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}



</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection
