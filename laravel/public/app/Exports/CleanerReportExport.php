<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;

class CleanerReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $dateFrom;
    protected $dateTo;
    protected $cleanerId;
    protected $communityId;

    public function __construct($dateFrom, $dateTo, $cleanerId, $communityId)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->cleanerId = $cleanerId;
        $this->communityId = $communityId;
    }

    public function collection()
    {
        // Aquí seleccionamos todos los servicios que corresponden al cleaner, con filtros
        return Service::select(
            'services.*',
            'types.description as type_description',
            'communities.community_name as community_name',
            DB::raw('COALESCE(types.commission, 0) as service_commission'),
            DB::raw('COALESCE(extras.commission, 0) as extra_commission')
        )
        ->join('types', 'services.type_id', '=', 'types.id')
        ->join('communities', 'services.community_id', '=', 'communities.id')
        ->leftJoin('extras_by_service', 'services.id', '=', 'extras_by_service.service_id')
        ->leftJoin('extras', 'extras_by_service.extra_id', '=', 'extras.id')
        ->when($this->dateFrom, function ($query) {
            $query->whereDate('services.date', '>=', $this->dateFrom);
        })
        ->when($this->dateTo, function ($query) {
            $query->whereDate('services.date', '<=', $this->dateTo);
        })
        ->when($this->cleanerId, function ($query) {
            $query->where('services.cleaner_id', $this->cleanerId);
        })
        ->when($this->communityId, function ($query) {
            $query->where('services.community_id', $this->communityId);
        })
        ->get()
        ->map(function ($service) {
            return [
                'Community' => $service->community_name,
                'Unit Number' => $service->unit_number,
                'Type' => $service->type_description,
                'Service Commission' => $service->service_commission,
                'Extra Commission' => $service->extra_commission,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Community',
            'Unit Number',
            'Type',
            'Service Commission',
            'Extra Commission',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilos para el encabezado
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('D2:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
    }
}
