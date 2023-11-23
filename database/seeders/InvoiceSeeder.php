<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
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
            $idkID = $index;
            DB::table('invoices')->insert([
                'id_detail_kunjungan' => $idkID,
                'catatan' => $faker->text,
                'receipt_file_path' => $faker->filePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
