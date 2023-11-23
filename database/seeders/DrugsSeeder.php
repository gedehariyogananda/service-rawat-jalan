<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DrugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drugs = ['amoxilin', 'diapet', 'paracetamol', 'bodrex', 'paramex', 'kalpanak'];
        foreach ($drugs as $drug) {
            \App\Models\Drugs::create([
                'name' => $drug,
                'price' => 5000,
                'name_code' => Str::uuid(),
                'how_to_use' => 'dimakan sama pisang',
                'side_effect' => 'bikin mabok',
                'stock' => 50
            ]);
        }
    }
}
