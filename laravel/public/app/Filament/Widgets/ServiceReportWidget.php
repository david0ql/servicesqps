<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Exports\ServiceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CleanerReportExport;
use App\Exports\CombinedCleanerReportExport;
use App\Models\User;
use App\Exports\MultiSheetCleanerReportExport;
use App\Exports\CommunityReportExport;
use App\Models\Cost;

class ServiceReportWidget extends TableWidget
{
    protected static string $view = 'filament.widgets.service-report';

    protected int | string | array $columnSpan = 'full';
    protected function getTableQuery(): Builder
    {
        return Service::query()
            ->with('type', 'community', 'cleaner', 'extras', 'status')
            ->select(
                'services.*',
                'types.price as type_price',
                'types.commission as type_commission',
                DB::raw('(SELECT COALESCE(SUM(extras.item_price), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id) as extra_price'),
                DB::raw('(SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id) as extra_commission'),
                DB::raw('COALESCE(types.commission, 0) + COALESCE((SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id), 0) as total_cleaner'),
                DB::raw('COALESCE(types.price, 0) - COALESCE(types.commission, 0) - COALESCE((SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id), 0) as total_before_partner'),
                DB::raw('(COALESCE(types.price, 0) - COALESCE(types.commission, 0) - COALESCE((SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id), 0)) * 0.40 as partner'),
                DB::raw('COALESCE(types.price, 0) - (COALESCE(types.commission, 0) + COALESCE((SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id), 0)) - ((COALESCE(types.price, 0) - COALESCE(types.commission, 0) - COALESCE((SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id), 0)) * 0.40) as total')
            )
            ->join('types', 'services.type_id', '=', 'types.id')
            ->join('communities', 'services.community_id', '=', 'communities.id')
            ->leftJoin('users', 'services.cleaner_id', '=', 'users.id')
            ->leftJoin('statuses', 'services.status_id', '=', 'statuses.id');
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('date_from')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date_from')->label('From Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['date_from'])) {
                        $query->whereDate('services.date', '>=', $data['date_from']);
                    }
                }),
            Tables\Filters\Filter::make('date_to')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date_to')->label('To Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['date_to'])) {
                        $query->whereDate('services.date', '<=', $data['date_to']);
                    }
                }),
            Tables\Filters\Filter::make('status')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->options(\App\Models\Status::all()->pluck('status_name', 'id'))
                        ->label('Status'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['status'])) {
                        $query->where('services.status_id', $data['status']);
                    }
                }),

            Tables\Filters\Filter::make('cleaner')
                ->form([
                    \Filament\Forms\Components\Select::make('cleaner')
                        ->options(\App\Models\User::whereHas('roles', function ($query) {
                            $query->where('name', 'Cleaner');
                        })->pluck('name', 'id'))
                        ->label('Cleaner'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['cleaner'])) {
                        $query->where('services.cleaner_id', $data['cleaner']);
                    }
                }),

            Tables\Filters\Filter::make('community')
                ->form([
                    \Filament\Forms\Components\Select::make('community')
                        ->options(\App\Models\Community::all()->pluck('community_name', 'id'))
                        ->label('Community'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['community'])) {
                        $query->where('services.community_id', $data['community']);
                    }
                }),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('date')->label('Date')->date('m-d-Y'),
            Tables\Columns\TextColumn::make('community.community_name')->label('Community'),
            Tables\Columns\TextColumn::make('type.description')->label('Type'),
            Tables\Columns\TextColumn::make('unit_number')->label('Unit Number'),
            Tables\Columns\TextColumn::make('type_price')->label('Service Price')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('type_commission')->label('Service Commission')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('extra_price')->label('Extras Price')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('extra_commission')->label('Extras Commission')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('total_cleaner')->label('Total Cleaner')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('partner')->label('Partner')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('total')->label('Total')->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
            Tables\Columns\TextColumn::make('cleaner.name')->label('Cleaner'),
            Tables\Columns\TextColumn::make('status.status_name')->label('Status'),
        ];
    }


    protected function getTableHeaderActions(): array
    {
        return [

            Tables\Actions\Action::make('export_general')
                ->label('Export General Reports')
                ->action(function () {


                    $filterState = $this->getTableFiltersForm()->getState();

                    $dateFrom = $filterState['date_from'] ?? null;
                    $dateTo = $filterState['date_to'] ?? null;
                    $status = $filterState['status'] ?? null;
                    $cleaner = $filterState['cleaner'] ?? null;
                    $community = $filterState['community'] ?? null;

                    $dateFrom2 = $dateFrom['date_from'];
                    $dateTo2 = $dateTo['date_to'];

                    $costs = Cost::query()
                    ->when(!is_null($dateFrom2), function ($query) use ($dateFrom2) {
                        $query->whereDate('date', '>=', $dateFrom2);
                    })
                    ->when(!is_null($dateTo2), function ($query) use ($dateTo2) {
                        $query->whereDate('date', '<=', $dateTo2);
                    })
                    ->get()
                    ->toArray();

                    $generalReports = Service::query()
                        ->with('type', 'community', 'cleaner', 'extras', 'status')
                        ->select(
                            'services.date as date',
                            'communities.community_name',
                            'types.description as type',
                            'services.unit_number',
                            'types.price as type_price',
                            'types.commission as type_commission',
                            DB::raw('(SELECT COALESCE(SUM(extras.item_price), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id) as extra_price'),
                            DB::raw('(SELECT COALESCE(SUM(extras.commission), 0) FROM extras JOIN extras_by_service ON extras.id = extras_by_service.extra_id WHERE extras_by_service.service_id = services.id) as extra_commission'),
                            DB::raw('COALESCE(users.name, "Unknown") as cleaner_name'),
                            DB::raw('COALESCE(statuses.status_name, "Unknown") as status_name')
                        )
                        ->join('types', 'services.type_id', '=', 'types.id')
                        ->join('communities', 'services.community_id', '=', 'communities.id')
                        ->leftJoin('users', 'services.cleaner_id', '=', 'users.id')
                        ->leftJoin('statuses', 'services.status_id', '=', 'statuses.id')
                        ->when($dateFrom2, function ($query) use ($dateFrom2) {
                            $query->whereDate('date', '>=', $dateFrom2);
                        })
                        ->when($dateTo2, function ($query) use ($dateTo2) {
                            $query->whereDate('date', '<=', $dateTo2);
                        })
                        ->orderBy('date', 'asc')
                        ->get()
                        ->toArray();

                    $reportData = []; // Cambié aquí para asegurar que este arreglo se inicia vacío
                    foreach ($generalReports as $service) {
                        $reportData[] = [
                            'Date' => $service['date'],
                            'Community' => $service['community_name'],
                            'Type' => $service['type'],
                            'Unit Number' => $service['unit_number'],
                            'Service Price' => $service['type_price'],
                            'Service Commission' => $service['type_commission'],
                            'Extras Price' => $service['extra_price'],
                            'Extras Commission' => $service['extra_commission'],
                            'Total Cleaner' => $service['type_commission'] + $service['extra_commission'],
                            'Felix_Hijo' => 0.20 * ($service['type_price'] - $service['type_commission'] - $service['extra_commission']),
                            'Partner' => 0.20 * ($service['type_price'] - $service['type_commission'] - $service['extra_commission']),
                            'Total' => $service['type_price'] - $service['type_commission'] - $service['extra_commission'] -
                                    (0.20 * ($service['type_price'] - $service['type_commission'] - $service['extra_commission'])) -
                                    (0.20 * ($service['type_price'] - $service['type_commission'] - $service['extra_commission'])),
                            'Cleaner' => $service['cleaner_name'],
                            'Status' => $service['status_name'],
                        ];
                    }

                    $export = new ServiceReportExport($reportData, $costs);
                    $filePath = $export->export();

                    $archivo = public_path('general_report.xlsx');

                    return response()->stream(function () use ($archivo) {
                        return readfile($archivo);
                    }, 200, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition' => 'attachment; filename="general_report.xlsx"',
                    ]);
                }),


            Tables\Actions\Action::make('export_cleaner_reports')
                ->label('Export Cleaner Reports')
                ->action(function () {
                    // Obtén todos los cleaners
                    $cleaners = User::whereHas('roles', function ($query) {
                        $query->where('name', 'Cleaner');
                    })->get();

                    $filterState = $this->getTableFiltersForm()->getState();

                    $dateFrom = $filterState['date_from'] ?? null;
                    $dateTo = $filterState['date_to'] ?? null;
                    $status = $filterState['status'] ?? null;
                    $cleaner = $filterState['cleaner'] ?? null;
                    $community = $filterState['community'] ?? null;

                    $cleanerReports = [];

                    foreach ($cleaners as $cleaner) {
                        $services = Service::query()
                            ->with('type', 'community', 'extras', 'status')
                            ->leftJoin('statuses', 'services.status_id', '=', 'statuses.id')
                            ->select(
                                'services.date as date',
                                'services.unit_number',
                                'communities.community_name',
                                'types.description as type_description',
                                'types.commission as type_commission',
                                DB::raw('(SELECT COALESCE(SUM(extras.commission), 0) FROM extras 
                                          JOIN extras_by_service ON extras.id = extras_by_service.extra_id 
                                          WHERE extras_by_service.service_id = services.id) as extra_commission')
                            )
                            ->join('types', 'services.type_id', '=', 'types.id')
                            ->join('communities', 'services.community_id', '=', 'communities.id')
                            ->when($dateFrom, function ($query) use ($dateFrom) {
                                $query->whereDate('services.date', '>=', $dateFrom);
                            })
                            ->when($dateTo, function ($query) use ($dateTo) {
                                $query->whereDate('services.date', '<=', $dateTo);
                            })
                            ->where('services.cleaner_id', $cleaner->id)
                            ->where('statuses.status_name', 'finished')
                            ->orderBy('services.date', 'asc')
                            ->get();

                        $reportData = [];

                        foreach ($services as $service) {
                            $totalCleaner = $service->type_commission + $service->extra_commission;

                            $reportData[] = [
                                'Date' => $service->date,
                                'Community' => $service->community_name,
                                'Unit Number' => $service->unit_number,
                                'Type' => $service->type_description,
                                'Service Commission' => $service->type_commission,
                                'Extra Commission' => $service->extra_commission,
                                'Total Cleaner' => $totalCleaner,
                            ];
                        }



                        $cleanerReports[$cleaner->name] = $reportData;
                    }

                    $export = new MultiSheetCleanerReportExport($cleanerReports);
                    $filePath = $export->export();

                    $archivo = public_path('cleaner_reports_generated.xlsx');

                    return response()->stream(function () use ($archivo) {
                        return readfile($archivo);
                    }, 200, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition' => 'attachment; filename="cleaner_reports_generated.xlsx"',
                    ]);
                }),

            Tables\Actions\Action::make('export_community_reports')
                ->label('Invoices')
                ->action(function () {
                    $communities = \App\Models\Community::all();

                    $filterState = $this->getTableFiltersForm()->getState();

                    $dateFrom = $filterState['date_from'] ?? null;
                    $dateTo = $filterState['date_to'] ?? null;
                    $status = $filterState['status'] ?? null;
                    $cleaner = $filterState['cleaner'] ?? null;

                    $communityReports = [];

                    foreach ($communities as $community) {
                        $services = Service::query()
                            ->leftJoin('statuses', 'services.status_id', '=', 'statuses.id')
                            ->with('type', 'cleaner')
                            ->where('services.community_id', $community->id)
                            ->when($dateFrom, function ($query) use ($dateFrom) {
                                $query->whereDate('date', '>=', $dateFrom);
                            })
                            ->when($dateTo, function ($query) use ($dateTo) {
                                $query->whereDate('date', '<=', $dateTo);
                            })
                            ->where('statuses.status_name', 'finished')
                            ->orderBy('date', 'asc')
                            ->get();

                        // Solo agrega la comunidad si hay servicios
                        if ($services->isNotEmpty()) {
                            $reportData = [];
                            foreach ($services as $service) {
                                $reportData[] = [
                                    'Date' => $service->date,
                                    'Unit Number' => $service->unit_number,
                                    'Type' => $service->type->description,
                                    'Service Value' => $service->type->price,
                                    'Cleaner' => $service->cleaner->name ?? 'Sin asignar',
                                ];
                            }

                            $communityReports[$community->community_name] = $reportData;
                        }
                    }

                    $export = new CommunityReportExport($communityReports);
                    $filePath = $export->export();

                    $archivo = public_path('community_reports_generated.xlsx');

                    return response()->stream(function () use ($archivo) {
                        return readfile($archivo);
                    }, 200, [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition' => 'attachment; filename="community_reports_generated.xlsx"',
                    ]);
                }),
        ];
    }
}
