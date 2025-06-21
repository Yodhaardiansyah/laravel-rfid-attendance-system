<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrayerTimeSeeder extends Seeder
{
    public function run()
    {
        $prayers = [
            ['name' => 'subuh', 'time' => '05:00:00'],
            ['name' => 'dzuhur', 'time' => '12:15:00'],
            ['name' => 'ashar', 'time' => '15:30:00'],
            ['name' => 'maghrib', 'time' => '18:00:00'],
            ['name' => 'isya', 'time' => '19:30:00'],
        ];

        foreach ($prayers as $prayer) {
            DB::table('prayer_times')->updateOrInsert(['name' => $prayer['name']], ['time' => $prayer['time']]);
        }
    }
}
