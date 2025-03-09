<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages\ListServices;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\View;
use App\Services\SmsService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),

                TimePicker::make('schedule')
                    ->label(__('Schedule'))
                    ->nullable(),

                Select::make('unity_size')
                    ->label(__('Unity Size'))
                    ->options([
                        'N/A' => 'N/A',
                        '1 Bedroom' => '1 Bedroom',
                        '2 Bedroom' => '2 Bedroom',
                        '3 Bedroom' => '3 Bedroom',
                        '4 Bedroom' => '4 Bedroom',
                        '5 Bedroom' => '5 Bedroom',
                    ])
                    ->required(),

                TextInput::make('unit_number')
                    ->label(__('Unit Number'))
                    ->required(),

                Select::make('community_id')
                    ->label(__('Community'))
                    ->relationship('community', 'community_name')
                    ->options(
                        $user->hasRole('super_admin') ?
                            \App\Models\Community::pluck('community_name', 'id') : // Muestra todas las comunidades para Super Admin
                            \App\Models\Community::where('manager_id', $user->id)->pluck('community_name', 'id') // Muestra comunidades asociadas al manager
                    )
                    ->required()
                    ->reactive() // Permite que el campo dependa de cambios
                    ->afterStateUpdated(fn ($state, callable $set) => $set('type_id', null)), // Resetea el tipo cuando la comunidad cambia

                Select::make('type_id')
                    ->label(__('Type'))
                    ->relationship('type', 'description')
                    ->options(function (callable $get) {
                        $communityId = $get('community_id');

                        // Obtener los tipos para la comunidad seleccionada
                        $types = \App\Models\Type::query()
                            ->where('community_id', $communityId)
                            ->orWhereNull('community_id')
                            ->orWhere('community_id', 0)
                            ->get();

                        // Crear un array de opciones para el campo select
                        $options = $types->mapWithKeys(function ($type) {
                            return [$type->id => $type->description . ' (' . $type->cleaning_type . ')'];
                        });

                        $options->prepend('N/A (N/A)', 'na');

                        return $options;
                    })
                    ->required(),

                Select::make('status_id')
                    ->label(__('Status'))
                    ->relationship('status', 'status_name')
                    ->options(
                        $user->hasRole('super_admin') ?
                            \App\Models\Status::pluck('status_name', 'id') :
                        ($user->hasRole('Manager') ?
                            \App\Models\Status::where('status_name', 'Created')->pluck('status_name', 'id') :
                        \App\Models\Status::whereIn('status_name', ['Approved', 'Rejected', 'Completed'])->pluck('status_name', 'id'))
                    )
                    ->required(),

                BelongsToManyMultiSelect::make('extras')
                    ->label(__('Extras'))
                    ->relationship('extras', 'item')
                    ->options(\App\Models\Extra::all()->pluck('item', 'id'))
                    ->nullable(),

                //validate if the user is a role Super Admin so he can assign a cleaner
                $user->hasRole('super_admin') ?
                Select::make('cleaner_id')
                    ->label(__('Cleaner'))
                    ->relationship('cleaner', 'name')
                    ->options(\App\Models\User::role('Cleaner')->pluck('name', 'id'))
                    ->nullable()
                : Hidden::make('cleaner_id'),

                Textarea::make('comment')
                    ->label(__('Comment')),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->sortable()
                    ->searchable()
                    ->date('m-d-Y'),

                TextColumn::make('schedule')
                    ->label(__('Schedule'))
                    ->sortable(),

                TextColumn::make('unity_size')
                    ->label(__('Unity Size')),

                TextColumn::make('unit_number')
                    ->label(__('Unit Number'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('community.community_name')
                    ->label(__('Community')),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->formatStateUsing(function ($record) {
                        return $record->type->description . ' (' . $record->type->cleaning_type . ')';
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status.status_name')
                    ->label(__('Status')),

                TextColumn::make('comment')
                    ->label(__('Comment')),

                TextColumn::make('type.commission')
                    ->label(__('Commission'))
                    ->formatStateUsing(function ($record) {
                        return $record->type->commission;
                    })
                    ->sortable()
                    ->searchable()
                    ->visible(fn ($record) => $user->hasRole('Cleaner')),

                TextColumn::make('extras')
                    ->label(__('Extras'))
                    ->formatStateUsing(function ($record) {
                        return $record->extras->pluck('item')->implode(', ');
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cleaner.name')
                    ->label(__('Cleaner'))
                    ->sortable()
                    ->searchable(),
            ])
            
            
            ->filters([
                Filter::make('date_from')
                    ->form([
                        DatePicker::make('date_from')->label('From'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['date_from']) {
                            $query->whereDate('date', '>=', $data['date_from']);
                        }
                    }),

                Filter::make('date_to')
                    ->form([
                        DatePicker::make('date_to')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['date_to']) {
                            $query->whereDate('date', '<=', $data['date_to']);
                        }
                    }),

                Filter::make('status')
                ->form([
                    Select::make('status')
                        ->options(\App\Models\Status::pluck('status_name', 'id')) // Cargar todos los estados, incluyendo 'finished'
                        ->label('Status'),
                ])
                ->query(function ($query, array $data) {
                    // Si se selecciona un estado específico
                    if (!empty($data['status'])) {
                        // Si el estado seleccionado es 'finished', solo filtra por ese estado
                        if ($data['status'] == 6) {
                            $query->where('status_id', 6);
                        } else {
                            // Filtra por los demás estados
                            $query->where('status_id', $data['status']);
                        }
                    } else {
                        // Si no hay selección, excluye finished por defecto
                        $query->where('status_id', '!=', 6);
                    }
                }),

                Filter::make('cleaner')
                    ->form([
                        Select::make('cleaner')
                            ->options(\App\Models\User::whereHas('roles', function ($query) {
                                $query->where('name', 'Cleaner');
                            })->pluck('name', 'id'))
                            ->label('Cleaner'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['cleaner'])) {
                            $query->where('cleaner_id', $data['cleaner']);
                        }
                    }),
            ])

            ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->action(function (Service $record) {
                        $record->update(['status_id' => \App\Models\Status::where('status_name', 'Approved')->first()->id]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Service $record) => auth()->user()->hasRole('Cleaner') && !in_array($record->status->status_name, ['Approved', 'Rejected', 'Completed'])),
            Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (Service $record) {
                        $record->update([
                            'status_id' => \App\Models\Status::where('status_name', 'Rejected')->first()->id,
                            'cleaner_id' => null,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Service $record) => auth()->user()->hasRole('Cleaner') && !in_array($record->status->status_name, ['Approved', 'Rejected', 'Completed'])),

            Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Service $record) {
                        $record->update(['status_id' => \App\Models\Status::where('status_name', 'Completed')->first()->id]);
                    })
                    ->requiresConfirmation()
                    ->visible(
                        fn (Service $record) =>
                        auth()->user()->hasRole('Cleaner') &&
                        $record->status->status_name === 'Approved'
                    ),

            Action::make('finish')
                    ->label('Finish')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Service $record) {
                        $record->update(['status_id' => \App\Models\Status::where('status_name', 'Finished')->first()->id]);
                    })
                    ->requiresConfirmation()
                    ->visible(
                        fn (Service $record) =>
                        auth()->user()->hasRole('super_admin') &&
                        $record->status->status_name === 'Completed'
                    ),
            Action::make('photos')
            ->label('Photos')
            ->icon('heroicon-o-photo')
            ->form(function (Service $record) {
                $serviceId = $record->id;
                // Ruta absoluta para guardar y buscar fotos
                $photoPath = "/home/services/public_html/photos/{$serviceId}";

                // Asegúrate de que la carpeta exista y tenga los permisos adecuados
                if (!File::exists($photoPath)) {
                    File::makeDirectory($photoPath, 0777, true);
                }

                // Obtén los archivos de la carpeta específica de fotos
                $photos = File::files($photoPath);

                // Generar URLs de las fotos
                $photoUrls = array_map(fn ($path) => url('photos/' . $serviceId . '/' . basename($path)), $photos);

                return [
                    FileUpload::make('photos')
                        ->multiple()
                        ->directory("photos/{$serviceId}")
                        ->preserveFilenames()
                        ->saveUploadedFileUsing(function ($file) use ($photoPath) {
                            // Guarda el archivo en la ruta absoluta 'public_html/photos/{serviceId}'
                            $filePath = $photoPath . '/' . $file->getClientOriginalName();
                            // Mueve el archivo al destino final
                            File::move($file->getPathname(), $filePath);
                            return str_replace(base_path('public_html/'), '', $filePath);
                        }),
                    // Mostrar las fotos subidas
                    View::make('components.photo-gallery')
                        ->viewData(['photos' => $photoUrls]),
                ];
            })
            ->action(function (Service $record, array $data) {
                // Acción lógica si es necesario
            })
            ->visible(fn (Service $record) => auth()->user()->hasRole('super_admin') || ($record->status->status_name === 'Approved' && auth()->user()->hasRole('Cleaner'))),


        ])
            ->defaultSort('date');
    }




    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
