<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Proprietaire;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\ProprietaireExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProprietaireResource\Pages;
use App\Filament\App\Resources\ProprietaireResource\RelationManagers;


class ProprietaireResource extends Resource
{
    protected static ?string $model = Proprietaire::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom_proprietaire'),
                Forms\Components\TextInput::make('prenom_proprietaire'),

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
                Tables\Columns\TextColumn::make('code_proprietaire')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('nom_proprietaire')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('prenom_proprietaire')->sortable()->searchable()->toggleable(),

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

                Tables\Columns\TextColumn::make('lastModifiedBy.name')
                    ->label('Dernière modification par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('nom_proprietaire')
                ->label('Nom du Proprietaire')
                ->searchable()
                ->preload()
                ->options(fn () => Proprietaire::pluck('nom_proprietaire', 'nom_proprietaire')->toArray()),
            
                Tables\Filters\SelectFilter::make('prenom_proprietaire')
                ->label('Prenom du Proprietaire')
                ->searchable()
                ->preload()
                ->options(fn () => Proprietaire::pluck('prenom_proprietaire', 'prenom_proprietaire')->toArray()),                    

            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->headerActions([
                ExportAction::make()->label('Exporter tous les Propriétaires')->exporter(ProprietaireExporter::class),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
                ExportBulkAction::make()->label('Exporter les Propriétaires sélectionnés')->exporter(ProprietaireExporter::class)
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
            'index' => Pages\ListProprietaires::route('/'),
            'create' => Pages\CreateProprietaire::route('/create'),
            'edit' => Pages\EditProprietaire::route('/{record}/edit'),
        ];
    }



}
