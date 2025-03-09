<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = new Type();
        $type->description = 'STUDIO 1X1';
        $type->cleaning_type = 'Full Cleaning';
        $type->price = 145.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '1X1 + DEN';
        $type->cleaning_type = 'Full Cleaning';
        $type->price = 150.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '2X2';
        $type->cleaning_type = 'Full Cleaning';
        $type->price = 155.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '3X2';
        $type->cleaning_type = 'Full Cleaning';
        $type->price = 165.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = 'STUDIO 1X1';
        $type->cleaning_type = 'TouchUp Clean';
        $type->price = 105.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '1X1 + DEN';
        $type->cleaning_type = 'TouchUp Clean';
        $type->price = 110.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '2X2';
        $type->cleaning_type = 'TouchUp Clean';
        $type->price = 115.00;
        $type->community_id = 5;
        $type->save();

        $type = new Type();
        $type->description = '3X2';
        $type->cleaning_type = 'TouchUp Clean';
        $type->price = 120.00;
        $type->community_id = 5;
        $type->save();
    }
}
