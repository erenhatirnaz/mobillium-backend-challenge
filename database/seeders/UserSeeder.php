<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->admin()->create([
            'full_name' => 'Mobillium Admin',
            'email' => 'admin@mobillium.com',
            'password' => Hash::make('mobillium'),
        ]);
        User::factory()->writer()->create([
            'full_name' => 'Mobillium Writer',
            'email' => 'writer1@mobillium.com',
            'password' => Hash::make('mobillium'),
        ]);
    }
}
