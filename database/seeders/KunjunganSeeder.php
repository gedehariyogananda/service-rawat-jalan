<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;



class KunjunganSeeder extends Seeder
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
            $pasienId = $index; 

            DB::table('kunjungans')->insert([
                'pasien_id' => $pasienId,
                'keluhan' => $faker->sentence,
                'status_pembayaran' => 0,
                'tanggal_kunjungan' => $faker->date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
