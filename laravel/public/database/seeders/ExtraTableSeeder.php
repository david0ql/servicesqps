<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Extra;

class ExtraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $extra = new Extra();
        $extra->item = 'Oven';
        $extra->item_price = 15.00;
        $extra->save();

        $extra = new Extra();
        $extra->item = 'Fridge';
        $extra->item_price = 15.00;
        $extra->save();

        $extra = new Extra();
        $extra->item = 'More than two items';
        $extra->item_price = 40.00;
        $extra->save();

        $extra = new Extra();
        $extra->item = 'Extremely dirty';
        $extra->item_price = 50.00;
        $extra->save();
    }
}