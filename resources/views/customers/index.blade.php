@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Customer</h1>
        <button onclick="document.getElementById('modal-tambah-customer').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
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
        <table id="customersTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nomor HP</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $customer->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $customer->user->email ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $customer->phone_number }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $customer->address ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($customer))'>
                            Edit
                        </button>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                            onclick="confirmDelete({{ $customer->id }})">Hapus</button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Modal Tambah -->
<div id="modal-tambah-customer" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <!-- Close button -->
            <button onclick="document.getElementById('modal-tambah-customer').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah Customer</h2>

                <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('email')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('phone_number')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('password')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-customer').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                    </div>
                </form>

        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="modal-edit-customer" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-customer').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Customer</h2>

            <form id="editCustomerForm" method="POST">
                @csrf
                @method('PATCH')


                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="editName" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="editEmail" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('email')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" name="phone_number" id="editPhoneNumber" value="{{ old('phone_number') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('phone_number')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" id="editAddress" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password (biarkan kosong jika tidak diubah)</label>
                        <input type="password" name="password" id="editPassword" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('password')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="editPasswordConfirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                    </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-customer').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function openEditModal(customer) {
        document.getElementById('modal-edit-customer').classList.remove('hidden');
        document.getElementById('editCustomerForm').action = `/customers/${customer.id}`;

        const user = customer.user || {};
        document.getElementById('editName').value = user.name || '';
        document.getElementById('editEmail').value = user.email || '';
        document.getElementById('editPhoneNumber').value = customer.phone_number || '';
        document.getElementById('editAddress').value = customer.address || '';
        document.getElementById('editPassword').value = "";
        document.getElementById('editPasswordConfirmation').value = "";
    }

function confirmDelete(customerId) {
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
            document.getElementById(`delete-form-${customerId}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-customer').classList.remove('hidden');
    @endif
});

function resetForm() {
    const createForm = document.querySelector('#modal-tambah-customer form');
    if (createForm) {
        createForm.reset();
    }

    const editForm = document.getElementById('editCustomerForm');
    if (editForm) {
        editForm.reset();
    }
}

document.querySelector('#modal-tambah-customer .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-customer').classList.add('hidden');
});


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editCustomer'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editCustomer')));
        }
    </script>
@endif



@endsection
