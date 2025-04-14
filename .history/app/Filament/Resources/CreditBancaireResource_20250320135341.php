<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CreditBancaire;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CreditBancaireResource\Pages;
use App\Filament\Resources\CreditBancaireResource\RelationManagers;

class CreditBancaireResource extends Resource
{
    protected static ?string $model = CreditBancaire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        // depotdossier
                        Forms\Components\Checkbox::make('depotdossier')
                            ->label('Dépot de dossier')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('depotdossier_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('depotdossier_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
    
                        // comite
                        Forms\Components\Checkbox::make('comite')
                            ->label('Comité')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('comite_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('comite_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),

                        // paiementfrais
                        Forms\Components\Checkbox::make('paiementfrais')
                            ->label('Paiement des frais')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('paiementfrais_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('paiementfrais_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // signatureconvenrg
                        Forms\Components\Checkbox::make('signatureconvenrg')
                            ->label('Signature de la convention avec enregistrement')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('signatureconvenrg_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('signatureconvenrg_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),                            

                        // dossiertransfnotaire
                        Forms\Components\Checkbox::make('dossiertransfnotaire')
                            ->label('Dossier transféré au notaire')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('dossiertransfnotaire_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('dossiertransfnotaire_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),

                        // signvspclientgerant
                        Forms\Components\Checkbox::make('signvspclientgerant')
                            ->label('Signature VSP Client Gérant')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('signvspclientgerant_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('signvspclientgerant_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // recuperationcheque
                        Forms\Components\Checkbox::make('recuperationcheque')
                            ->label('Récupération du chèque')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('recuperationcheque_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('recuperationcheque_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // enrgvsp
                        Forms\Components\Checkbox::make('enrgvsp')
                            ->label('Enregistrement VSP')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('enrgvsp_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('enrgvsp_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // publicationvsp
                        Forms\Components\Checkbox::make('publicationvsp')
                            ->label('Publication VSP')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('publicationvsp_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('publicationvsp_modified_at')
                            ->label('Last Modified At')
                            ->disabled(),
                            
                        // paiementtranches
                        Forms\Components\Checkbox::make('paiementtranches')
                            ->label('Paiement des tranches')
                            ->default(false)
                            ->inline(false),
    
                        Forms\Components\DateTimePicker::make('paiementtranches_attributed_at')
                            ->label('First Attributed At')
                            ->disabled(),
    
                        Forms\Components\DateTimePicker::make('paiementtranches_modified_at')
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

                Tables\Columns\IconColumn::make('depotdossier')
                ->label('Dépot de dossier')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('comite')
                ->label('Comité')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('paiementfrais')
                ->label('Paiement des frais')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('signatureconvenrg')
                ->label('Signature de la convention avec enregistrement')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('dossiertransfnotaire')
                ->label('Dossier transféré au notaire')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('signvspclientgerant')
                ->label('Signature VSP Client Gérant')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('recuperationcheque')
                ->label('Récupération du chèque')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('enrgvsp')
                ->label('Enregistrement VSP')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('publicationvsp')
                ->label('Publication VSP')
                ->boolean()
                ->toggleable()
                ->sortable(),
                Tables\Columns\IconColumn::make('paiementtranches')
                ->label('Paiement des tranches')
                ->boolean()
                ->toggleable()
                ->sortable(),
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

                Tables\Filters\SelectFilter::make('depotdossier')
                ->label('Dépot de dossier')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('comite')
                ->label('Comité')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('paiementfrais')
                ->label('Paiement des frais')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('signatureconvenrg')
                ->label('Signature de la convention avec enregistrement')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('dossiertransfnotaire')
                ->label('Dossier transféré au notaire')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('signvspclientgerant')
                ->label('Signature VSP Client Gérant')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('recuperationcheque')
                ->label('Récupération du chèque')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('enrgvsp')
                ->label('Enregistrement VSP')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('publicationvsp')
                ->label('Publication VSP')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
                Tables\Filters\SelectFilter::make('paiementtranches')
                ->label('Paiement des tranches')
                ->options([
                    '1' => 'Oui',
                    '0' => 'Non',
                ])
                ->preload(),
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
            'index' => Pages\ListCreditBancaires::route('/'),
            'create' => Pages\CreateCreditBancaire::route('/create'),
            'edit' => Pages\EditCreditBancaire::route('/{record}/edit'),
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

}
