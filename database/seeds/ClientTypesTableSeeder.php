<?php

use Illuminate\Database\Seeder;

class ClientTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client_types')->insert([
            'id' => 1,
            'client_type' => 'Физ. лицо',
        ]);

        DB::table('client_types')->insert([
            'id' => 2,
            'client_type' => 'Юр. лицо',
        ]);
    }
}
