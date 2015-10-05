<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
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
            DB::table('categories')->insert([
                'slug' => str_slug($name),
                'name' => $name
            ]);
        }
    }
}
