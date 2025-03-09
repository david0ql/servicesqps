<?php

namespace App\Filament\Resources;

use App\Models\Type;
use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables;
use Filament\Forms;
use App\Filament\Resources\TypeResource\Pages;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Forms\Form $form): Forms\Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label(__('Description'))
                    ->required(),
                Forms\Components\TextInput::make('cleaning_type')
                    ->label(__('Cleaning Type'))
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('community_id')
                    ->label(__('Community'))
                    ->relationship('community', 'community_name')
                    ->options(\App\Models\Community::all()->pluck('community_name', 'id'))
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
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cleaning_type')
                    ->label(__('Type'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => '$' . $state),
                Tables\Columns\TextColumn::make('community.community_name')
                    ->label(__('Community'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('commission')
                    ->label(__('Commission'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => '$' . $state)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('description');
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTypes::route('/'),
            'create' => Pages\CreateType::route('/create'),
            'edit' => Pages\EditType::route('/{record}/edit'),
        ];
    }
}
