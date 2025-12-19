@extends('layouts.appCostumer')

@section('content')
    <main class="flex-grow">

        {{-- Hero Section --}}
        <section class="w-full bg-[#C94544] py-20 px-6 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                Selamat Datang di <span class="text-yellow-300">Bumdes Mekarsari</span>
            </h1>
            <p class="text-lg md:text-xl max-w-3xl mx-auto opacity-90">
                Platform jual beli online untuk mendukung UMKM lokal dan pemberdayaan desa.
            </p>

            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ auth()->check() ? route('productsCustomer.index') : route('login') }}"
                    class="px-6 py-3 bg-yellow-300 text-[#C94544] font-semibold rounded-lg shadow hover:bg-yellow-400 transition">
                    Belanja Sekarang
                </a>
            </div>
        </section>

        {{-- Keunggulan --}}
        <section class="py-16 px-6 max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Kenapa Belanja di Bumdes?</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="text-[#C94544]  text-4xl mb-3">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Produk Lokal</h3>
                    <p class="text-gray-600">Mendukung usaha kecil dan menengah milik masyarakat desa.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="text-[#C94544]  text-4xl mb-3">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Harga Terjangkau</h3>
                    <p class="text-gray-600">Produk langsung dari pengrajin dan petani tanpa perantara.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <div class="text-[#C94544]  text-4xl mb-3">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Pengiriman Cepat</h3>
                    <p class="text-gray-600">Layanan antar langsung dari desa ke rumah Anda.</p>
                </div>
            </div>
        </section>

        {{-- Tentang --}}
        <section class="py-16 px-6 max-w-5xl mx-auto" id="tentang">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Tentang Bumdes</h2>
            <p class="text-gray-700 text-lg leading-relaxed text-center">
                Badan Usaha Milik Desa (Bumdes) adalah lembaga yang dibentuk desa untuk mengelola potensi ekonomi.
                Dengan hadirnya platform jual beli online ini, kami membantu UMKM lokal memperluas jangkauan
                pemasaran dan meningkatkan kesejahteraan masyarakat desa.
            </p>
        </section>

    </main>
@endsection
