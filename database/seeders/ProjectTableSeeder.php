<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('projects')->insert([
            ['id' => 92848653, 'name' => 'Productive'],
            ['id' => 154629151, 'name' => 'Workout'],
            ['id' => 157099012, 'name' => 'Meditation'],
        ]);
    }
}
