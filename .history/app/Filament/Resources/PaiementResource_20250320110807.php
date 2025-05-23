<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Paiement;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\PaiementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaiementResource\RelationManagers;

class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Tables';

    // Define the record title attribute for global search
    protected static ?string $recordTitleAttribute = 'codepaie';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'codepaie',
            'codeclient',
            'client.nomclient',  // Search by nomclient from the clients table
            'client.prenomclient' // Search by prenomclient from the clients table
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
        if (!empty($record->achat?->codachat)) {
            $details['Code Achat'] = $record->achat->codachat;
        }
        if (!empty($record->banque?->codebanque)) {
            $details['Banque'] = "{$record->banque->codebanque} ({$record->banque->nomdebanque})";
        }
        if (!empty($record->modepaie)) {
            $details['Mode Paiement'] = $record->modepaie;
        }
        if (!empty($record->montantpaie)) {
            $details['Montant Payé'] = number_format($record->montantpaie, 2, ',', ' ') . ' DZD';
        }        
        if (!empty($record->datepaie)) {
            $details['Date de Paiement'] = date('d-m-Y', strtotime($record->datepaie));
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
                Forms\Components\Select::make('modepaie')
                ->options([
                    'Crédit Bancaire' => 'Crédit Bancaire',
                    'Espece' => 'Espece',
                    'Versement' => 'Versement',
                    'Remboursement' => 'Remboursement',
                ]),
                Forms\Components\Select::make('codebanque')
                ->searchable()
                ->preload()
                ->relationship('banque','codebanque')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque} {$record->adressedebanque})"),
                Forms\Components\TextInput::make('montantpaie')->numeric()->inputMode('decimal'),
                Forms\Components\DatePicker::make('datepaie')
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('codeclient')
                ->searchable()
                ->preload()
                ->relationship('client','codeclient')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})"),
                Forms\Components\Select::make('codachat')
                ->searchable()
                ->preload()
                ->relationship('achat','codachat'),

                Section::make('Indication Temporelle')->schema([
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
                    
                    Forms\Components\Select::make('last_modified_by')
                    ->label('Dernière modification par')
                    ->relationship('lastModifiedBy', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}")
                    ->disabled(),
                                   
                    Forms\Components\Select::make('deleted_by')
                    ->label('Supprimé par')
                    ->relationship('deletedBy', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}")
                    ->disabled()
                    ->visible(fn ($record) => !is_null($record?->deleted_by)),
                ])->columns(2),               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codepaie')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('modepaie')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codebanque')
                ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})")
                ->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('montantpaie')
                ->money('DZD')
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('datepaie')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codeclient')->sortable()->searchable()->toggleable()
                ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})"),
                Tables\Columns\TextColumn::make('codachat')->sortable()->searchable()->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Supprimé le')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('lastModifiedBy.name')
                    ->label('Dernière modification par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->label('Supprimé par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),    

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('modepaie')
                    ->label('Mode de Paie')
                    ->options([
                        'Crédit Bancaire' => 'Crédit Bancaire',
                        'Espece' => 'Espece',
                        'Versement' => 'Versement',
                        'Remboursement' => 'Remboursement',
                    ])
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('codebanque')
                    ->label('Banque')
                    ->relationship('banque', 'codebanque')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque})")
                    ->preload(),
                Filter::make('datepaie')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('A partir de')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('created_until')
                            ->label('Jusqu à')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('datepaie', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('datepaie', '<=', $date),
                            );
                    })
                    ->indicateUsing(fn (array $data) => array_filter([
                        isset($data['created_from']) && $data['created_from'] !== '' 
                            ? 'A partir de: ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y') 
                            : null,
                        isset($data['created_until']) && $data['created_until'] !== '' 
                            ? 'Jusqu à: ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y') 
                            : null,
                    ])),
                Tables\Filters\SelectFilter::make('codeclient')
                    ->label('Client')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                    ->preload(),
                Tables\Filters\SelectFilter::make('codachat')
                    ->label('Achat')
                    ->relationship('achat', 'codachat')
                    ->searchable()
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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
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
        return parent::getGlobalSearchEloquentQuery()->withoutTrashed()->with(['client', 'achat', 'banque']);

    }

}
