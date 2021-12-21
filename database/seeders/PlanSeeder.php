<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use DB;

class PlanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    //php artisan db:seed --class=PlanSeeder
    public function run()
    {
       DB::table('plans')->insert(
           ['id'=>1, 
           'name'=>'Free forever plan', 
           'description'=>'Free forever plan',
           'price' => 0.00,
           'trial_days' => 0,
           'type' => 'recurring',
           'status' => 1,
           'created_at' => now(),
           'updated_at' => now()
       ]);
    }
}
