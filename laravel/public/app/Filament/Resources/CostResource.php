<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostResource\Pages;
use App\Models\Cost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use Filament\Tables\Columns\TextColumn;

class CostResource extends Resource
{
    protected static ?string $model = Cost::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters(static::getTableFilters())
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label('Export to Excel')
            ]);
    }

    protected static function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('date_from')
                ->form([
                    Forms\Components\DatePicker::make('date_from')->label('From Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['date_from'])) {
                        $query->whereDate('date', '>=', $data['date_from']);
                    }
                }),
            Tables\Filters\Filter::make('date_to')
                ->form([
                    Forms\Components\DatePicker::make('date_to')->label('To Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['date_to'])) {
                        $query->whereDate('date', '<=', $data['date_to']);
                    }
                }),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCosts::route('/'),
            'create' => Pages\CreateCost::route('/create'),
            'edit' => Pages\EditCost::route('/{record}/edit'),
        ];
    }

}
