<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Service;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function query(): Builder
    {
        $user = auth()->user();

        return $user->hasRole('Cleaner')
            ? Service::where('cleaner_id', $user->id)
            : (
                $user->hasRole('Manager')
                ? Service::whereIn('community_id', \App\Models\Community::where('manager_id', $user->id)->pluck('id'))
                : Service::query()
            );
    }
}