<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunityResource\Pages;
use App\Filament\Resources\CommunityResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\Community;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CommunityResource extends Resource
{
    protected static ?string $model = Community::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('community_name')
                    ->label(__('Community Name'))
                    ->required(),

                Select::make('manager_id')
                    ->label(__('Manager'))
                    ->relationship('manager', 'name')
                    ->options(\App\Models\User::role('manager')->pluck('name', 'id'))
                    ->required(),

                Select::make('company_id')
                    ->label(__('Company'))
                    ->relationship('company', 'company_name')
                    ->options(\App\Models\Company::all()->pluck('company_name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('community_name')
                    ->label(__('Community Name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('manager.name')
                    ->label(__('Manager')),

                TextColumn::make('company.company_name')
                    ->label(__('Company')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('community_name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunities::route('/'),
            'create' => Pages\CreateCommunity::route('/create'),
            'edit' => Pages\EditCommunity::route('/{record}/edit'),
        ];
    }
}