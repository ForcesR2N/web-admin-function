<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run()
    {
        DB::table('rooms')->insert([
            [
                'id' => 1,
                'name' => 'Ruang Pangripta',
                'imagepath' => 'img/ruangpangripta.jpg',
                'status' => 'available',
                'description' => 'Ini Ruang Pangripta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Ruang Tengah',
                'imagepath' => 'img/ruangtengah.jpg',
                'status' => 'available',
                'description' => 'Ini Ruang Tengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Ruang Aula',
                'imagepath' => 'img/ruangaula.jpg',
                'status' => 'available',
                'description' => 'Ini Ruang Aula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
