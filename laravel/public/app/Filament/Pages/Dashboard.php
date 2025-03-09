<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Resources\ServiceResource\Widgets\ServiceReportWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            ServiceReportWidget::class,
        ];
    }
}
