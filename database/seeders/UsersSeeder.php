<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Database\Factories\UserFactory;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $adminUser = User::create([
            'po_box_number' => 'A001',
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '+123456789',
            'password' => bcrypt('12345678'), 
            // Change to your desired password
        ]);

        // Assign the 'admin' role to the admin user
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser->assignRole($adminRole);


        User::factory()->create([
            'id' => 2,
            'po_box_number' =>  "U002",
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
