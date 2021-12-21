<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TranslateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::unprepared( file_get_contents( "public/sql/translates.sql" ) );
    }
}
