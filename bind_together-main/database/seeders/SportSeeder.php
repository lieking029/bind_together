<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            'Volleyball',
            'Basketball',
            'Swimming',
            'Badminton',
            'Table Tennis',
            'Chess',
            'Sepak Takraw',
            'Taekwondo',
            'Arnis',
            'Running',
            'Darts',
            'Javelin Throw',
            'Shot put',
            'E-Sports (MLBB)',
            'Dance Sports'
        ];

        // Loop through the sports array and create each one
        foreach ($sports as $sportName) {
            Sport::create([
                'name' => $sportName,
            ]);
        }
    }
}
