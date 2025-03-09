<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Service;

class Calendar extends Page
{
    protected static string $view = 'filament.pages.calendar';

    protected static ?string $slug = 'calendar'; // Asegúrate de que sea ?string

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Calendar';


    public array $services = []; // Inicializa la propiedad como un array vacío

    public function mount(): void
    {
        $this->services = Service::with('cleaner', 'community')
            ->get()
            ->map(function ($service) {
                return [
                    'title' => (optional($service->cleaner)->name ?? 'No asignado') . "(" . (optional($service->community)->community_name ?? 'No disponible') . ")",
                    'start' => $service->date,
                    'description' => 'Comunidad: ' . optional($service->community)->community_name ?? 'No disponible',
                    'unit_number' => 'Unit number: '. $service->unit_number,
                    'status' => optional($service->status)->status_name ?? 'No disponible',
                    'id' => $service->id,
                ];
            })->toArray(); // Convierte la colección a un array solo al final
    }

}
