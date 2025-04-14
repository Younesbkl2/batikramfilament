<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActFinalResource\Pages;
use App\Filament\Resources\ActFinalResource\RelationManagers;
use App\Models\ActFinal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;

class ActFinalResource extends Resource
{
    protected static ?string $model = ActFinal::class;

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListActFinals::route('/'),
            'create' => Pages\CreateActFinal::route('/create'),
            'edit' => Pages\EditActFinal::route('/{record}/edit'),
        ];
    }
}
