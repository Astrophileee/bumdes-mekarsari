@extends('layouts.appCostumer')

@section('content')
<main class="flex-grow px-6 py-10 bg-gray-50">

    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8 text-center">
        Daftar Produk Bumdes Mekarsari
    </h1>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse ($products as $product)
            <a href="{{ route('productsCustomer.show', $product) }}"
               class="block bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition">

                {{-- Gambar Produk --}}
                <div class="h-40 w-full bg-gray-100 overflow-hidden">
                    @if ($product->photo)
                        <img src="{{ asset('storage/' . $product->photo) }}"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-cover hover:scale-105 transition">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-gray-400">
                            Tidak ada gambar
                        </div>
                    @endif
                </div>

                {{-- Detail Produk --}}
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 line-clamp-1">
                        {{ $product->name }}
                    </h3>

                    <p class="text-green-600 font-bold text-lg mt-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    @if ($warehouses->current_stock > 0)
                        <p class="text-sm text-gray-600 mt-1">
                            Stok(KG): <span class="font-medium">{{ $warehouses->current_stock }}</span>
                        </p>
                    @else
                        <p class="text-sm text-red-600 font-medium mt-1">Stok Habis</p>
                    @endif

                    {{-- Tombol Beli --}}
                    <button class="w-full mt-4 py-2 bg-[#C94544] text-white rounded-lg hover:bg-[#a63636] transition">
                        Lihat Detail
                    </button>
                </div>
            </a>

        @empty
            <p class="text-center text-gray-600 col-span-4">Tidak ada produk tersedia.</p>
        @endforelse

    </div>

</main>
@endsection
