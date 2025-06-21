<?php

// database/seeders/SantriSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;

class SantriSeeder extends Seeder
{
    public function run()
    {
        Santri::create([
            'name' => 'Ahmad Zainudin',
            'rfid_number' => 'RFID001',
            'year' => '2025',
        ]);
        
        Santri::create([
            'name' => 'Budi Santoso',
            'rfid_number' => 'RFID002',
            'year' => '2024',
        ]);

        Santri::create([
            'name' => 'Citra Dewi',
            'rfid_number' => 'RFID003',
            'year' => '2025',
        ]);
    }
}
