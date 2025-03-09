<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = new Status();
        $status->id = 1;
        $status->status_name = 'Created';
        $status->save();

        $status = new Status();
        $status->id = 2;
        $status->status_name = 'Pending';
        $status->save();

        $status = new Status();
        $status->id = 3;
        $status->status_name = 'Approved';
        $status->save();

        $status = new Status();
        $status->id = 4;
        $status->status_name = 'Rejected';
        $status->save();

        $status = new Status();
        $status->id = 5;
        $status->status_name = 'Completed';
        $status->save();
    }
}