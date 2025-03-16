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

    /**
     * Hacemos que ocupe todo el ancho disponible.
     */
    public function getColumns(): int | string | array
    {
        return 1; // Cambiar de 2 a 1 para que los widgets usen todo el ancho
    }
}
