<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Tables';

    // Define the record title attribute for global search
    protected static ?string $recordTitleAttribute = 'codeclient';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'codeclient',
            'nomclient',
            'prenomclient'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];
    
        if (!empty($record->client?->nomclient)) {
            $details['Nom'] = $record->client->nomclient;
        }
        if (!empty($record->client?->prenomclient)) {
            $details['Prenom'] = $record->client->prenomclient;
        }
        if (!empty($record->client?->date_de_naissance)) {
            $details['Date de Naissance'] = $record->client->date_de_naissance;
        }
        if (!empty($record->client?->Numdetel)) {
            $details['Téléphone 1'] = $record->client->Numdetel;
        }
        if (!empty($record->client?->NUM_TEL)) {
            $details['Téléphone 2'] = $record->client->NUM_TEL;
        }
    
        return $details;
    }


    public static function getNavigationBadge(): ?string
    {
        $totalCount = static::getModel()::count(); // Get total count
        $trashedCount = static::getModel()::onlyTrashed()->count(); // Get soft-deleted count
    
        // If there are soft-deleted entries, append the count in parentheses
        return $trashedCount > 0 ? "{$totalCount} (Corbeille: {$trashedCount})" : (string) $totalCount;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codeclient')->required(),
                Forms\Components\TextInput::make('nomclient')->required(),
                Forms\Components\TextInput::make('prenomclient')->required(),
                Forms\Components\TextInput::make('adresseclient'),
                Forms\Components\TextInput::make('Numdetel')->tel(),
                Forms\Components\TextInput::make('NUM_TEL')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\DatePicker::make('date_de_naissance')
                ->maxDate(now())
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),                
                Forms\Components\FileUpload::make('photo')->directory('photos'),
                Forms\Components\FileUpload::make('dossier')
                ->directory('pdfs')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(10240)
                ->visibility('public'),
                Forms\Components\Checkbox::make('Vsp_publié')->default(false),

                Forms\Components\Placeholder::make('spacer') // Acts as an empty space
                ->label(' '), // Empty label to keep spacing

                Forms\Components\DateTimePicker::make('created_at')
                ->label('Créé le (Mois/Jour/Année)')
                ->disabled(),
            
                Forms\Components\DateTimePicker::make('updated_at')
                ->label('Mis à jour le (Mois/Jour/Année)')
                ->disabled(),
                
                Forms\Components\DateTimePicker::make('deleted_at')
                ->label('Supprimé le (Mois/Jour/Année)')
                ->disabled()
                ->visible(fn ($record) => !is_null($record?->deleted_at)),                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codeclient')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('nomclient')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('prenomclient')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('adresseclient')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Numdetel')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('NUM_TEL')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(),
                Tables\Columns\ImageColumn::make('photo')
                ->circular()
                ->toggleable()
                ->disk('public') // Ensures it reads from the correct disk
                ->extraAttributes(['class' => 'cursor-pointer'])
                ->url(fn ($state) => $state ? asset('storage/' . $state) : null) // Ensures a valid URL
                ->openUrlInNewTab(),
                Tables\Columns\IconColumn::make('dossier')
                ->icon(fn ($state) => $state ? 'heroicon-o-document-text' : 'heroicon-o-x')
                ->url(fn ($state) => $state ? asset('storage/' . $state) : null, true)
                ->openUrlInNewTab()
                ->toggleable(),
                Tables\Columns\TextColumn::make('date_de_naissance')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\IconColumn::make('Vsp_publié')
                ->boolean()
                ->sortable()
                ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),                   

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('codeclient')
                    ->label('Client')
                    ->searchable()
                    ->options(fn () => Client::pluck('codeclient', 'codeclient')->toArray())
                    ->preload(),
                Tables\Filters\SelectFilter::make('nomclient')
                    ->label('Nom du Client')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Client::pluck('nomclient', 'nomclient')->toArray()),
                
                Tables\Filters\SelectFilter::make('prenomclient')
                    ->label('Prenom du Client')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Client::pluck('prenomclient', 'prenomclient')->toArray()), 
                // Numdetel - Case-Insensitive Search with Active Filter Display
                Filter::make('Numdetel')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Num de Téléphone')
                            ->placeholder('Enter le Num de Téléphone')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Numdetel) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Num de Téléphone: ' . $data['value'] 
                            : null
                    ),
                // NUM_TEL - Case-Insensitive Search with Active Filter Display
                Filter::make('NUM_TEL')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Num de Téléphone 2')
                            ->placeholder('Enter le Num de Téléphone 2')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(NUM_TEL) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Num de Téléphone 2: ' . $data['value'] 
                            : null
                    ),
                    Filter::make('date_de_naissance')
                    ->form([
                        Forms\Components\DatePicker::make('value')
                            ->label('Date de Naissance')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereDate('date_de_naissance', '=', $data['value'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Date de Naissance: ' . \Carbon\Carbon::parse($data['value'])->format('d/m/Y')
                            : null
                    ),
                    Tables\Filters\SelectFilter::make('Vsp_publié')
                    ->label('Vsp Publié')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),                                                                                                                                             

                TrashedFilter::make(),
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function query(Builder $query): Builder
    {
        return $query->withoutGlobalScopes(); // Shows all records (both soft-deleted & active)
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
    

    
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->withoutTrashed()->with(['client', 'appartement', 'parking', 'local', 'produit', 'banque']);

    }


    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->withoutTrashed();

    }  

}
