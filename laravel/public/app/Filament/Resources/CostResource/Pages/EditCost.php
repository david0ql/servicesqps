<?php

namespace App\Filament\Resources\CostResource\Pages;

use App\Filament\Resources\CostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCost extends EditRecord
{
    protected static string $resource = CostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
