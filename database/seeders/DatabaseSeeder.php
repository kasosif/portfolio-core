<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'role' => 'ADMIN',
                'name' => 'Khalil Fakhfekh',
                'email' => 'kasosif@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$BvInx74ZtugqXTHBetff9.p7pw0fIcBxcTod4DX4InTRwILlWpPM6'
            ]
        );
    }
}
