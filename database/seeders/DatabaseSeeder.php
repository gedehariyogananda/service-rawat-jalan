<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DetailKunjungan;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Room;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Faker\Provider\en_US\Text;
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

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Siti Rahayu',
            'email' => 'siti.rahayu@gmail.com',
            'roles' => 'perawat',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Ahmad Yudha',
            'email' => 'ahmad.yudha@gmail.com',
            'roles' => 'perawat',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Maria Dewi',
            'email' => 'maria.dewi@gmail.com',
            'roles' => 'perawat',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Budi Pratama',
            'email' => 'budi.pratama@gmail.com',
            'roles' => 'perawat',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Indah Sari',
            'email' => 'indah.sari@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Michael Smith',
            'email' => 'michael.smith@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Emily Davis',
            'email' => 'emily.davis@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Robert Anderson',
            'email' => 'robert.anderson@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Jennifer Lee',
            'email' => 'jennifer.lee@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. David Brown',
            'email' => 'david.brown@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Lisa Martinez',
            'email' => 'lisa.martinez@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. James Wilson',
            'email' => 'james.wilson@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Maria Garcia',
            'email' => 'maria.garcia@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Richard Taylor',
            'email' => 'richard.taylor@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'no_pegawai' => Str::uuid(),
            'name' => 'Dr. Smith Lambada',
            'email' => 'smith.lambada@gmail.com',
            'roles' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        Room::create([
            'no_room' => '0001',
            'name_room' => 'Ruang Perawatan Intensif (ICU)',
        ]);

        Room::create([
            'no_room' => '0002',
            'name_room' => 'Ruang Isolasi',
        ]);

        Room::create([
            'no_room' => '0003',
            'name_room' => 'Ruang Persalinan',
        ]);

        Room::create([
            'no_room' => '0004',
            'name_room' => 'Ruang Hemodialisis',
        ]);

        Room::create([
            'no_room' => '0005',
            'name_room' => 'Ruang Perawatan Jangka Panjang',
        ]);

        Room::create([
            'no_room' => '0006',
            'name_room' => 'Ruang Konseling',
        ]);

        Room::create([
            'no_room' => '0007',
            'name_room' => 'Ruang Kesehatan Mental:',
        ]);

        Room::create([
            'no_room' => '0008',
            'name_room' => 'Perawatan Jangka Panjang',
        ]);

        Room::create([
            'no_room' => '0009',
            'name_room' => 'Fisioterapi',
        ]);

        Room::create([
            'no_room' => '0010',
            'name_room' => 'Ruang Perawatan Anak',
        ]);

        $poli = [
            'Poli Umum',
            'Poli Mata',
            'Poli Gigi',
            'Poli THT',
            'Poli Kandungan',
            'Poli Anak',
            'Poli Kulit',
            'Poli Kesehatan Mental',
            'Poli Penyakit Menular',
            'Poli Jantung'
        ];

        foreach ($poli as $item) {
            Poli::create([
                'name_poli' => $item
            ]);
        }



        $this->call([UserSeeder::class, DrugsSeeder::class, PasienSeeder::class, KunjunganSeeder::class, LabSedeer::class]);
    }
}
