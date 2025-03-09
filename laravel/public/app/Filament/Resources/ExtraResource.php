<?php

namespace App\Filament\Resources;

use App\Models\Extra;
use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables;
use Filament\Forms;
use App\Filament\Resources\ExtraResource\Pages;

class ExtraResource extends Resource
{
    protected static ?string $model = Extra::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item')
                    ->label(__('Item'))
                    ->required(),
                Forms\Components\TextInput::make('item_price')
                    ->label(__('Item Price'))
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('commission')
                    ->label(__('Commission'))
                    ->numeric()
                    ->default(0)
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item')
                    ->label(__('Item'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_price')
                    ->label(__('Item Price'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => '$' . $state),
                Tables\Columns\TextColumn::make('commission')
                    ->label(__('Commission'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => '$' . $state),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('item');
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtras::route('/'),
            'create' => Pages\CreateExtra::route('/create'),
            'edit' => Pages\EditExtra::route('/{record}/edit'),
        ];
    }
}
