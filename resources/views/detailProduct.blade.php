@extends('layouts.appCostumer')

@section('content')
<main class="flex-grow py-10 px-4">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-md grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="flex justify-center items-center">
            <img src="{{ asset('storage/' . $product->photo) }}"
                 alt="Product Image"
                 class="rounded-xl w-full max-w-sm shadow">
        </div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ $product->name }}</h1>

            <p class="text-gray-600 mb-4 leading-relaxed">
                {{ $product->description }}
            </p>

            <div class="mb-4">
                <span class="text-xl font-semibold text-green-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            <div class="mb-4">
                <span class="text-sm text-gray-500">Stok tersedia (KG): </span>
                <span class="font-semibold">{{ $warehouses->current_stock }}</span>
            </div>
            {{-- Form beli --}}
            <form action="{{ route('transaction.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" id="" value="{{ $product->id }}">
                <input type="hidden" name="customer_id" id="" value="{{ Auth::user()->customer->id }}">


                {{-- Qty --}}
                <div>
                    <label class="block font-medium mb-1">Jumlah Beli</label>
                    <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $warehouses->current_stock }}" class="w-28 p-2 border rounded">
                </div>

                {{-- Total price --}}
                <div>
                    <label class="block font-medium mb-1">Total Harga</label>
                    <input type="text" name="total_price" id="total_price" readonly class="w-full p-2 border rounded bg-gray-100 font-semibold" value="Rp {{ number_format($product->price, 0, ',', '.') }}">
                </div>

                {{-- Button --}}
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold">
                    Tambah ke Keranjang
                </button>
            </form>

        </div>
    </div>
</main>

<script>
    const qty = document.getElementById('qty');
    const totalPrice = document.getElementById('total_price');
    const price = {{ $product->price }};

    qty.addEventListener('input', () => {
        let jumlah = qty.value;
        if (jumlah < 1) qty.value = 1;

        let total = jumlah * price;

        totalPrice.value = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection
