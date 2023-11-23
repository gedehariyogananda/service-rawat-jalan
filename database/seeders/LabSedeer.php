<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Lab::create([
            'code_lab' => 'LAB-DR',
            'name' => 'Lab Darah',
            'description' => 'Lab untuk pengecekan darah',
            'price' => 100000
        ]);
    }
}
