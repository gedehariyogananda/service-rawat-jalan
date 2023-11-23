<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DetailKunjunganSeeder extends Seeder
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
            $kId = $index;
            DB::table('detail_kunjungans')->insert([
                'kunjungan_id' => $kId,
                'user_id' => 2,
                'diagnosa' => $faker->sentence,
                'lab_id' => null,
                'resep' => null,
                'pembayaran' => 30000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
