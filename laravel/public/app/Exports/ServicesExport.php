<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromQuery, WithMapping, WithHeadings
{
    public function query()
    {
        return Service::query()->with('type', 'community', 'cleaner', 'extras', 'status');
    }

    public function map($service): array
    {
        return [
            $service->date,
            $service->community->community_name,
            $service->type->description,
            $service->type->cleaning_type,
            $service->unit_number,
            '$' . number_format($service->type_price, 2),
            '$' . number_format($service->type_commission, 2),
            '$' . number_format($service->extra_price, 2),
            '$' . number_format($service->extra_commission, 2),
            '$' . number_format($service->total_cleaner, 2),
            '$' . number_format($service->partner, 2),
            '$' . number_format($service->total, 2),
            $service->cleaner->name,
            $service->status->status_name,
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Community',
            'Type',
            'Cleaning Type',
            'Unit Number',
            'Service Price',
            'Service Commission',
            'Extras Price',
            'Extras Commission',
            'Total Cleaner',
            'Partner',
            'Total',
            'Cleaner',
            'Status',
        ];
    }
}