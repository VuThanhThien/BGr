<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class StoreCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       PaymentMethod::create([
            'name' => 'Store Credit',
            'key' => 'discount_coupon',
            'type' => 0,
            'shop_id' => 0
       ]);
    }
}
