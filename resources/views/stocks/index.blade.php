@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Riwayat Stok Beras</h1>
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
        <table id="stocksTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock Awal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perubahan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock Akhir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($stocks as $stock)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $stock->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $stock->initial_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $stock->change_amount }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $stock->final_stock }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $stock->notes ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $stock->created_at }}</td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

<script>


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif



@endsection
