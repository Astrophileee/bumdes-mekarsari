@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Gudang Beras</h1>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock Saat Ini</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($warehouses as $wh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $wh->current_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $wh->updated_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($wh))'>
                            Edit Stock Manual
                        </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

<!-- Modal Edit -->
<div id="modal-edit-warehouse" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-warehouse').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Stok digudang</h2>

            <form id="editWarehouseForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok Saat Ini</label>
                        <input type="number" name="current_stock" id="editStock" value="{{ old('current_stock') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('stock')
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan Perubahan* </label>
                        <textarea name="notes" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-product').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>


    function openEditModal(wh) {
        document.getElementById('modal-edit-warehouse').classList.remove('hidden');
        document.getElementById('editWarehouseForm').action = `/warehouses/${wh.id}`;

        document.getElementById('editStock').value = wh.current_stock;

    }



</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection
