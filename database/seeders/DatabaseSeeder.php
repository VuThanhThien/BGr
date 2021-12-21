<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $defaultMethods = config('myconfig.constants.payment_method_default');
        foreach($defaultMethods as $method) {
            PaymentMethod::create([ 'name' => $method['name'], 'key'=>$method['key'], 'fields' => $method['fields'], 'type' => 0, 'shop_id' => 0 ]);
        }
    }
}
