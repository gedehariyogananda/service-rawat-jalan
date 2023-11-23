<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('pasiens')->insert([
                'nama' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tanggal_lahir' => $faker->date,
                'no_hp' => $faker->numerify('##########'),
                'alamat' => $faker->address,
                'no_ktp' => $faker->unique()->randomNumber(9),
                'no_bpjs' => $faker->unique()->randomNumber(8),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
