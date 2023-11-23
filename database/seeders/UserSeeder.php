<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['staff', 'dokter', 'admin', 'teknisi','perawat'];
        foreach ($roles as $role) {
            \App\Models\User::factory()->create([
                'no_pegawai' => Str::uuid(),
                'name' => 'Test ' . $role,
                'email' => $role . '@gmail.com',
                'roles' => $role,
                'password' => Hash::make('password')
            ]);
        }

    }
}
