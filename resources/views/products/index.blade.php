@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Product</h1>
        <button onclick="document.getElementById('modal-tambah-product').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
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
        <table id="productsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 flex items-center space-x-3">
                            <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $product->photo) }}" alt="Avatar">
                            <div class="font-medium text-gray-900">{{ $product->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($product->price, 0, ',', '.')}}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $product->description ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($product))'>
                            Edit
                        </button>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                            onclick="confirmDelete({{ $product->id }})">Hapus</button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Modal Tambah -->
<div id="modal-tambah-product" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <!-- Close button -->
            <button onclick="document.getElementById('modal-tambah-product').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah </h2>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto (optional)</label>
                        <div class="border-2 border-dashed rounded-lg p-4 text-center">
                            <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="hidden" id="photoInput" onchange="previewPhoto(event)">
                            <label for="photoInput" class="cursor-pointer inline-block px-4 py-2 bg-gray-100 rounded-md text-sm font-medium text-gray-700">
                                Upload file
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Hanya mendukung format file: .jpg, .jpeg, .png</p>
                            <div id="photoPreviewContainer" class="mt-4 hidden">
                                <img id="photoPreview" class="mx-auto w-24 h-24 rounded-full object-cover border" alt="Preview Foto">
                            </div>
                        </div>
                        @error('photo')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" id="priceInput"  value="{{ old('price') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm" required>
                            <input type="hidden" name="price" id="priceRaw">
                    </div>

                    <!-- description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (opsional)</label>
                        <textarea name="description" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-product').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                    </div>
                </form>

        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="modal-edit-product" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-product').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Product</h2>

            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')


                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto (optional)</label>
                        <div class="border-2 border-dashed rounded-lg p-4 text-center">
                            <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="hidden" id="editPhotoInput" onchange="previewEditPhotoFunc(event)">
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

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="editName" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="text" id="editPriceInput"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm" required>
                        <input type="hidden" name="price" id="editPriceRaw">
                    </div>

                    <!-- description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (opsional)</label>
                        <textarea name="description" id="editDescription" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('description') }}</textarea>
                        @error('description')
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
    function previewPhoto(event) {
        const input = event.target;
        const preview = document.getElementById('photoPreview');
        const container = document.getElementById('photoPreviewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('hidden');
            preview.src = '';
        }
    }

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


    priceInput.addEventListener('input', function (e) {
        let number = e.target.value.replace(/[^0-9]/g, "");

        priceRaw.value = number;

        if (number) {
            e.target.value = formatRupiah(number);
        } else {
            e.target.value = "";
        }
    });

    function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(angka);
    }


    editPriceInput.addEventListener('input', function (e) {
        let number = e.target.value.replace(/[^0-9]/g, "");

        editPriceRaw.value = number;

        e.target.value = number ? formatRupiah(number) : "";
    });


    function openEditModal(product) {
        document.getElementById('modal-edit-product').classList.remove('hidden');
        document.getElementById('editProductForm').action = `/products/${product.id}`;

        document.getElementById('editName').value = product.name;
        document.getElementById('editDescription').value = product.description;
        editPriceRaw.value = product.price;
        editPriceInput.value = formatRupiah(product.price);

        const preview = document.getElementById('previewEditPhoto');
        preview.src = product.photo ? `/storage/${product.photo}` : '#';
        preview.style.display = product.photo ? 'block' : 'none';
        document.getElementById('editPhotoInput').value = "";
    }

function confirmDelete(productId) {
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
            document.getElementById(`delete-form-${productId}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-product').classList.remove('hidden');
    @endif
});

function resetForm() {
    const form = document.querySelector('#modal-tambah-product form');
    form.reset();

    const photoPreview = document.getElementById('photoPreview');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');
    photoPreviewContainer.classList.add('hidden');
    photoPreview.src = '';
    const photoInput = document.getElementById('photoInput');
    photoInput.value = '';
}

document.querySelector('#modal-tambah-product .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-product').classList.add('hidden');
});


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editProduct'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editProduct')));
        }
    </script>
@endif



@endsection
