<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        // === Buat admin ===
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => bcrypt('password'),
            ]
        );
        $customerUser->assignRole($customerRole);
        Customer::firstOrCreate(
            ['user_id' => $customerUser->id],
            [
                'phone_number' => '081234567891',
                'address' => 'BTN',
            ]
        );
        Warehouse::firstOrCreate(
            [
                'current_stock' => 0,
            ]
        );
    }
}
