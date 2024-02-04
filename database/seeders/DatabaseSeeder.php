<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Language;
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
        $english = Language::create([
            'code' => 'en',
            "name" => 'English'
        ]);
        Language::create([
            'code' => 'fr',
            "name" => 'Français'
        ]);
        Language::create([
            'code' => 'ar',
            "name" => 'العربية'
        ]);
        $candidate = Candidate::create([
            'first_name' => 'Khalil',
            'last_name' => 'Fakhfekh',
            'email' => 'kasosif@gmail.com',
            'job_description' => 'FullStack Laravel/React Developer',
            'phone_number' => '+21655740911',
            'address' => 'Olympic City, Tunis',
            'activated' => 1
        ]);
        $candidate->languages()->attach($english->id);
        User::create(
            [
                'role' => 'ADMIN',
                'name' => 'Khalil Fakhfekh',
                'email' => 'kasosif@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$N3MdxxzHafflZYgtWh/sFuy7rrBeGRtYikGjPb7wJKKa25mwmIaBq', // 12345
                'candidate_id' => $candidate->id
            ]
        );

    }
}
