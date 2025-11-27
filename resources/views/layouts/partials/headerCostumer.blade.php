<header class="bg-white shadow px-6 py-4 relative flex items-center">

    {{-- Kolom Kiri: Logo --}}
    <div class="flex items-center flex-1">
        <img src="{{ asset('logo-bumdes.jpg') }}" alt="Logo Bumdes"
             class="h-10 w-auto object-contain">
        <p class="text-lg md:text-xl font-extrabold text-[#C94544] pl-1">
            Bumdes Mekarsari
        </p>
    </div>

    {{-- Kolom Tengah: Navigator --}}
    @auth
        <nav class="absolute left-1/2 transform -translate-x-1/2 hidden md:flex gap-8
                    text-sm font-medium text-gray-700 z-50">
            <a href="{{ url('/') }}" class="hover:text-[#C94544]">Beranda</a>
            <a href="{{ route('productsCustomer.index') }}" class="hover:text-[#C94544]">Produk</a>
            <a href="{{ route('transaction.history.index') }}" class="hover:text-[#C94544]">Riwayat Pembelian</a>
        </nav>
    @endauth

    {{-- Kolom Kanan: Profile/Login --}}
    <div class="relative flex items-center gap-4 flex-1 justify-end">
        @if(Auth::check())
            <button type="button" onclick="toggleDropdown()"
                class="flex items-center gap-2 text-gray-700 font-medium focus:outline-none">
                <span>{{ Auth::user()->name }}</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>

            {{-- Dropdown --}}
            <div id="dropdownMenu"
                class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg hidden z-50">

                <div class="flex items-center gap-3 px-4 py-3 border-b">
                    <div>
                        <div class="font-semibold text-sm text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt text-sm"></i> Log out
                    </button>
                </form>
            </div>

        @else
            <a href="{{ route('login') }}"
            class="inline-block px-5 py-1.5 text-black hover:text-[#C94544] text-sm leading-normal">
                Login
            </a>

            <a href="{{ route('register') }}"
            class="inline-block px-5 py-1.5 text-white bg-[#C94544] hover:bg-[#A83433] rounded text-sm leading-normal">
                Register
            </a>
        @endif
    </div>

</header>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('dropdownMenu');
        const button = event.target.closest('button');

        if (!dropdown.contains(event.target) && (!button || !button.closest('.relative'))) {
            dropdown.classList.add('hidden');
        }
    });
</script>
