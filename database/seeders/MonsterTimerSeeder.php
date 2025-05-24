<?php

namespace Database\Seeders;

use App\Models\Timer;
use Illuminate\Database\Seeder;

class MonsterTimerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monsters = [
            [
                'name' => 'Chertuba',
                'location' => 'Gludio - Chertuba\'s Barracks',
                'level' => 40,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Awakened Chertuba',
                'location' => 'Chertuba\'s Barracks',
                'level' => 55,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Basila',
                'location' => 'Southern Wasteland',
                'level' => 40,
                'delay_minutes' => 240, // 4 hours
            ],
            [
                'name' => 'Kelsus',
                'location' => 'Ruins of Despair',
                'level' => 40,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Savan',
                'location' => 'Ant Nest (B2)',
                'level' => 45,
                'delay_minutes' => 720, // 12 hours
            ],
            [
                'name' => 'Queen Ant',
                'location' => 'Ant Nest (B3)',
                'level' => 50,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Tromba',
                'location' => 'Bloodstained Swampland',
                'level' => 60,
                'delay_minutes' => 420, // 7 hours
            ],
            [
                'name' => 'Felis',
                'location' => 'Dion - Bee Hive',
                'level' => 40,
                'delay_minutes' => 180, // 3 hours
            ],
            [
                'name' => 'Talakin',
                'location' => 'Rebel Territory',
                'level' => 40,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Enkura',
                'location' => 'Dion Plains',
                'level' => 40,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Pan Dra\'eed',
                'location' => 'Dion Hills',
                'level' => 40,
                'delay_minutes' => 720, // 12 hours
            ],
            [
                'name' => 'Mutated Cruma',
                'location' => 'Cruma Marshlands',
                'level' => 50,
                'delay_minutes' => 480, // 8 hours
            ],
            [
                'name' => 'Tempest',
                'location' => 'Morgue',
                'level' => 45,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Contaminated Cruma',
                'location' => 'Cruma Tower (B4)',
                'level' => 45,
                'delay_minutes' => 480, // 8 hours
            ],
            [
                'name' => 'Katan',
                'location' => 'Cruma Tower (B6)',
                'level' => 55,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Core Susceptor',
                'location' => 'Cruma Tower (B7)',
                'level' => 60,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Sarka',
                'location' => 'Delu Dwellings',
                'level' => 45,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Timitris',
                'location' => 'Floran Fields',
                'level' => 40,
                'delay_minutes' => 480, // 8 hours
            ],
            [
                'name' => 'Stonegeist',
                'location' => 'Giants\' Vestige',
                'level' => 45,
                'delay_minutes' => 420, // 7 hours
            ],
            [
                'name' => 'Gahareth',
                'location' => 'Tanor Canyon',
                'level' => 70,
                'delay_minutes' => 540, // 9 hours
            ],
            [
                'name' => 'Medusa',
                'location' => 'Medusa Garden',
                'level' => 55,
                'delay_minutes' => 600, // 10 hours
            ],
            [
                'name' => 'Black Lily',
                'location' => 'Death Pass',
                'level' => 65,
                'delay_minutes' => 720, // 12 hours
            ],
            [
                'name' => 'Matura',
                'location' => 'Pillagers\' Outpost',
                'level' => 50,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Breka',
                'location' => 'Breka\'s Stronghold',
                'level' => 50,
                'delay_minutes' => 360, // 6 hours
            ],
            [
                'name' => 'Pan Narod',
                'location' => 'Gorgon Flower Garden',
                'level' => 50,
                'delay_minutes' => 300, // 5 hours
            ],
            [
                'name' => 'Behemoth',
                'location' => 'Dragon Valley (North)',
                'level' => 65,
                'delay_minutes' => 540, // 9 hours
            ],
            [
                'name' => 'Dragon Beast',
                'location' => 'Antharas\' Lair (B6)',
                'level' => 75,
                'delay_minutes' => 720, // 12 hours
            ],
        ];

        foreach ($monsters as $monster) {
            Timer::create($monster);
        }
    }
}
