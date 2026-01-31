<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = \App\Models\Roles::where('name', 'admin')->first();

        User::create(['name' => 'admin', 
                      'email' => 'zakifajar@outlook.com',
                      'password' => Hash::make('admin123'),
                      'role_id' => $roleAdmin->id]);
    }
}
