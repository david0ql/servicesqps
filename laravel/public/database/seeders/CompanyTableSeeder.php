<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = new Company();
        $company->id = 1;
        $company->company_name = 'Graystar Management Services';
        $company->save();

        $company = new Company();
        $company->id = 2;
        $company->company_name = 'Willow Bridge Property Company';
        $company->save();

        $company = new Company();
        $company->id = 3;
        $company->company_name = 'Venterra Realty';
        $company->save();

        $company = new Company();
        $company->id = 4;
        $company->company_name = 'Panther Residential Management';
        $company->save();

        $company = new Company();
        $company->id = 5;
        $company->company_name = 'Aspen Square';
        $company->save();
    }
}