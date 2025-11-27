@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Hasil Panen Beras</h1>
        <button onclick="document.getElementById('modal-tambah-harvest').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
            Tambah
        </button>
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
        <table id="harvestsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Berat Masuk(KG)</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kualitas</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sumber Panen</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Harga(KG)</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($harvests as $harvest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $harvest->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $harvest->weight_in }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $harvest->quality }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $harvest->harvest_source ?? '-'}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($harvest->price_per_kg, 0, ',', '.')}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($harvest->total_price, 0, ',', '.')}}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $harvest->notes ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($harvest))'>
                            Edit
                        </button>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $harvest->id }}" action="{{ route('harvests.destroy', $harvest->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                            onclick="confirmDelete({{ $harvest->id }})">Hapus</button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

<!-- Modal Tambah -->
<div id="modal-tambah-harvest" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">

            <button onclick="document.getElementById('modal-tambah-harvest').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah Hasil Panen</h2>

            <form action="{{ route('harvests.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date') }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Weight In -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Berat Masuk (KG)</label>
                    <input type="number" step="0.01" name="weight_in" id="tambahWeightIn"
                        value="{{ old('weight_in') }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"
                        oninput="calculateTotalTambah()">
                </div>

                <!-- Quality -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kualitas</label>
                    <input type="text" name="quality" value="{{ old('quality') }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Harvest Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sumber Panen (opsional)</label>
                    <input type="text" name="harvest_source" value="{{ old('harvest_source') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Price Per KG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per KG</label>
                    <input type="text" name="price_per_kg" id="tambahPricePerKg"
                    value="{{ old('price_per_kg') }}" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"
                    oninput="formatTambahPrice(this)">
                </div>

                <!-- Total Price (readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Harga</label>
                    <input type="text" name="total_price" id="tambahTotalPrice" readonly
                    class="w-full border border-gray-300 bg-gray-100 rounded-md px-3 py-2 mt-1 text-sm">

                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <textarea name="notes"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('notes') }}</textarea>
                </div>

                <!-- Action -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-harvest').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="modal-edit-harvest" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">

            <button onclick="resetForm(); document.getElementById('modal-edit-harvest').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Edit Hasil Panen</h2>

            <form id="editHarvestForm" method="POST">
                @csrf
                @method('PATCH')

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" id="editDate" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Weight -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Berat Masuk (KG)</label>
                    <input type="number" step="0.01" name="weight_in" id="editWeightIn" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"
                        oninput="calculateTotalEdit()">
                </div>

                <!-- Quality -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kualitas</label>
                    <input type="text" name="quality" id="editQuality" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sumber Panen (opsional)</label>
                    <input type="text" name="harvest_source" id="editHarvestSource"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Price per KG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per KG</label>
                    <input step="0.01"  type="text" name="price_per_kg" id="editPricePerKg" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"
                        oninput="formatEditPrice(this)">
                </div>

                <!-- Total Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Harga</label>
                    <input type="text" step="0.01" name="total_price" id="editTotalPrice" readonly
                        class="w-full border border-gray-300 bg-gray-100 rounded-md px-3 py-2 mt-1 text-sm">
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <textarea name="notes" id="editNotes"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-harvest').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>




<script>
    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(angka);
    }

    function extractNumber(str) {
        return parseInt(str.replace(/[^0-9]/g, "")) || 0;
    }

    function formatTambahPrice(el) {
        let raw = extractNumber(el.value);
        el.value = raw ? formatRupiah(raw) : "";

        calculateTotalTambah();
    }

    function formatEditPrice(el) {
        let raw = extractNumber(el.value);
        el.value = raw ? formatRupiah(raw) : "";

        calculateTotalEdit();
    }

    function calculateTotalTambah() {
        let weight = parseFloat(document.getElementById('tambahWeightIn').value) || 0;
        let priceRaw = extractNumber(document.getElementById('tambahPricePerKg').value);
        document.getElementById('tambahTotalPrice').value = formatRupiah(weight * priceRaw);
    }

    function calculateTotalEdit() {
        let weight = parseFloat(document.getElementById('editWeightIn').value) || 0;
        let priceRaw = extractNumber(document.getElementById('editPricePerKg').value);
        document.getElementById('editTotalPrice').value = formatRupiah(weight * priceRaw);
    }



    function openEditModal(h) {
        document.getElementById('modal-edit-harvest').classList.remove('hidden');
        document.getElementById('editHarvestForm').action = `/harvests/${h.id}`;

        document.getElementById('editDate').value = h.date;
        document.getElementById('editWeightIn').value = h.weight_in;
        document.getElementById('editQuality').value = h.quality;
        document.getElementById('editHarvestSource').value = h.harvest_source ?? '';
        document.getElementById('editNotes').value = h.notes;
        document.getElementById('editPricePerKg').value = formatRupiah(h.price_per_kg);
        document.getElementById('editTotalPrice').value = formatRupiah(h.total_price);


        calculateTotalEdit();
    }

function confirmDelete(harvestId) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${harvestId}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-harvest').classList.remove('hidden');
    @endif
});

function resetForm() {
    const form = document.querySelector('#modal-tambah-harvest form');
    form.reset();
}

document.querySelector('#modal-tambah-harvest .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-harvest').classList.add('hidden');
});


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editHarvest'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editHarvest')));
        }
    </script>
@endif



@endsection
