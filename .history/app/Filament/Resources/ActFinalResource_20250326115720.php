<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ActFinal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ActFinalExporter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Resources\ActFinalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ActFinalResource\RelationManagers;

class ActFinalResource extends Resource
{
    protected static ?string $model = ActFinal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Suivi Client';


    protected static ?string $recordTitleAttribute = 'id';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'id',
            'codeclient',
            'codebanque',
            'client.nomclient',
            'client.prenomclient',
            'banque.nomdebanque',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->client) {
            $details['Client'] = "{$record->client->nomclient} {$record->client->prenomclient}";
        }

        if ($record->banque) {
            $details['Banque'] = $record->banque->nomdebanque;
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
                Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('codeclient')
                    ->searchable()
                    ->preload()
                    ->relationship('client','codeclient')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})"),

                    Forms\Components\Select::make('codebanque')
                    ->searchable()
                    ->preload()
                    ->relationship('banque','codebanque')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque} {$record->adressedebanque})"),
 
                ]),
                Forms\Components\Grid::make(3) // 3-column layout
                    ->schema([
                        // depotcahierplusattestremisecles
                        Forms\Components\Checkbox::make('depotcahierplusattestremisecles')
                            ->label('Dépôt du cahier des charges et attestation de remise des clés')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('depotcahierplusattestremisecles_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('depotcahierplusattestremisecles_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
    
                        // signactfinal
                        Forms\Components\Checkbox::make('signactfinal')
                            ->label('Signature acte final')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('signactfinal_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('signactfinal_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),

                        // enrgactfinal
                        Forms\Components\Checkbox::make('enrgactfinal')
                            ->label('Enregistrement acte')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('enrgactfinal_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('enrgactfinal_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // remisedescles
                        Forms\Components\Checkbox::make('remisedescles')
                            ->label('Remise des clés')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('remisedescles_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('remisedescles_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),                            
              

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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codeclient')->sortable()->searchable()->toggleable()
                ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})"),
                Tables\Columns\TextColumn::make('codebanque')
                ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})")
                ->sortable()->searchable()->toggleable(),

                Tables\Columns\IconColumn::make('depotcahierplusattestremisecles')
                ->label('Dépôt du cahier des charges et attestation de remise des clés')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('signactfinal')
                ->label('Signature acte final')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('enrgactfinal')
                ->label('Enregistrement acte')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('remisedescles')
                ->label('Remise des clés')
                ->boolean()
                ->toggleable()
                ->sortable(),

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
                Tables\Filters\SelectFilter::make('codeclient')
                ->label('Client')
                ->relationship('client', 'codeclient')
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                ->preload(),
                Tables\Filters\SelectFilter::make('codebanque')
                ->label('Banque')
                ->relationship('banque', 'codebanque')
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque})")
                ->preload(),

                Tables\Filters\SelectFilter::make('depotcahierplusattestremisecles')
                ->label('Dépôt du cahier des charges et attestation de remise des clés')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('signactfinal')
                ->label('Signature acte final')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('enrgactfinal')
                ->label('Enregistrement acte')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('remisedescles')
                ->label('Remise des clés')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),

                TrashedFilter::make(),

            ])
            ->recordUrl(null)
            ->headerActions([
                ExportAction::make()->label('Exporter tous les Act Finals')->exporter(ActFinalExporter::class),
            ])
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
                ExportBulkAction::make()->label('Exporter les Act Finals sélectionnés')->exporter(ActFinalExporter::class)
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
            'index' => Pages\ListActFinals::route('/'),
            'create' => Pages\CreateActFinal::route('/create'),
            'edit' => Pages\EditActFinal::route('/{record}/edit'),
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
        return parent::getGlobalSearchEloquentQuery()
            ->withoutTrashed()
            ->with(['client', 'banque']);
    }   

}
