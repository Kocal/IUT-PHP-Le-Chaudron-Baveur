<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class seed_categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = ['Vêtements', 'Bijoux', 'Baguettes magiques', 'Figurines', 'Balais', 'DVD', 'Divers'];

        foreach ($datas as $name) {
            DB::table('user_types')->insert([
                'slug' => str_slug($name),
                'name' => $name
            ]);
        }
    }
}
