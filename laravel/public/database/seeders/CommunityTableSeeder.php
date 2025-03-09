<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Community;

class CommunityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $community = new Community();
        $community->id = 1;
        $community->community_name = 'The Jamison';
        $community->manager_id = 2;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 2;
        $community->community_name = 'Summerwell Avian Point';
        $community->manager_id = 3;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 3;
        $community->community_name = 'Kestra Aprtments';
        $community->manager_id = 4;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 4;
        $community->community_name = 'Zen Apartments';
        $community->manager_id = 4;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 5;
        $community->community_name = 'Prime Luxury Apartments';
        $community->manager_id = 5;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 6;
        $community->community_name = 'LTD Championsridge';
        $community->manager_id = 6;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 7;
        $community->community_name = 'Oventure Hamlin';
        $community->manager_id = 7;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 8;
        $community->community_name = 'Encore Metro At Milenia';
        $community->manager_id = 9;
        $community->company_id = 1;
        $community->save();

        $community = new Community();
        $community->id = 9;
        $community->community_name = 'Urbana';
        $community->manager_id = 10;
        $community->company_id = 1;
        $community->save();


        $community = new Community();
        $community->id = 10;
        $community->community_name = 'Overture Dr. Phillips';
        $community->manager_id = 8;
        $community->company_id = 1;
        $community->save();



        /* $community = new Community();
        $community->id = 10;
        $community->community_name = 'The Grove at Clermont Apartments';
        $community->manager_id = ;
        $community->company_id = 3;
        $community->save();

        $community = new Community();
        $community->id = 11;
        $community->community_name = 'Integra Heights At Olympus';
        $community->manager_id = ;
        $community->company_id = 4;
        $community->save();

        $community = new Community();
        $community->id = 12;
        $community->community_name = 'Soleil Blu Luxury Apartments';
        $community->manager_id = ;
        $community->company_id = 5;
        $community->save(); */
    }
}