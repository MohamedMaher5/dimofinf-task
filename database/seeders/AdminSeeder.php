<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Admin::whereEmail(['admin@admin.com'])->exists()) {
            Admin::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]);
        }
    }
}
