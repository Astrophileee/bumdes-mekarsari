@extends('layouts.appCostumer')

@section('content')
<main class="flex-grow px-6 py-10 bg-gray-50">

    <h1 class="text-2xl md:text-3xl font-bold text-[#C94544] mb-8 text-center">
        History Transaksi
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <table id="historiesTable" class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $t)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $t->product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $t->qty }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        <span class="
                            px-3 py-1 rounded text-white
                            @if($t->payment_status === 'pending') bg-yellow-500
                            @elseif($t->payment_status === 'paid') bg-green-600
                            @elseif($t->payment_status === 'reject') bg-red-600
                            @elseif($t->payment_status === 'waiting' ) bg-orange-600
                            @endif
                        ">
                            {{ ucfirst($t->payment_status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                        <span class="
                        px-3 py-1 rounded text-white
                        @if($t->status === 'pending') bg-yellow-500
                        @elseif($t->status === 'completed') bg-green-600
                        @elseif($t->status === 'cancelled') bg-red-600
                        @elseif($t->status === 'delivery' ) bg-orange-600
                        @endif
                        ">
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">

                        @if($t->payment_status === 'pending')
                            <button
                                onclick="openModal({{ $t->id }})"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Upload Bukti
                            </button>
                        @endif

                        @if($t->payment_status === 'reject')
                            <button
                                onclick="openModal({{ $t->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Upload Ulang Bukti
                            </button>
                        @endif

                        @if($t->payment_status === 'paid')
                            <span class="text-green-600 font-semibold">Lunas</span>
                        @endif

                        @if($t->payment_status === 'waiting')
                            <span class="text-orange-600 font-semibold">Menunggu Konfirmasi</span>
                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-600">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">

        <h2 class="text-xl font-bold mb-4">Upload Bukti Pembayaran</h2>

        <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Foto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                <div class="border-2 border-dashed rounded-lg p-4 text-center">
                    <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png" class="hidden" id="editPhotoInput" onchange="previewEditPhotoFunc(event)">
                    <label for="editPhotoInput" class="cursor-pointer inline-block px-4 py-2 bg-gray-100 rounded-md text-sm font-medium text-gray-700">
                        Upload file
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Hanya mendukung format file: .jpg, .jpeg, .png</p>
                    <div id="photoPreviewContainer" class="mt-4 hidden">
                        <img id="photoPreview" class="mx-auto w-24 h-24 rounded-full object-cover border" alt="Preview Foto">
                    </div>
                    <div id="previewEditPhotoContainer" class="mt-4">
                        <img id="previewEditPhoto" class="mx-auto w-24 h-24 rounded-full object-cover border" alt="Foto Sebelumnya" style="display: none;">
                    </div>
                </div>
                @error('photo')
                    <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Upload
                </button>
            </div>
        </form>

    </div>
</div>


<script>

    function previewEditPhotoFunc(event) {
        const input = event.target;
        const preview = document.getElementById('previewEditPhoto');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function openModal(transactionId) {
        let modal = document.getElementById('uploadModal');
        let form = document.getElementById('uploadForm');

        form.action = '/transaction/history/upload/' + transactionId;

        const preview = document.getElementById('previewEditPhoto');
        preview.src = transactionId.payment_proof ? `/storage/${transactionId.payment_proof}` : '#';
        preview.style.display = transactionId.payment_proof ? 'block' : 'none';
        document.getElementById('editPhotoInput').value = "";

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        let modal = document.getElementById('uploadModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

</main>
@endsection
