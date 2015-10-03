<?php

use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            ['admin', 'Administrateur'],
            ['someone', 'Particulier']
        ];

        foreach ($datas as $data) {
            DB::table('user_types')->insert([
                'slug' => $data[0],
                'name' => $data[1]
            ]);
        }
    }
}
