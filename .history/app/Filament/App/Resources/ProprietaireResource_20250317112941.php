<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProprietaireResource\Pages;
use App\Filament\App\Resources\ProprietaireResource\RelationManagers;
use App\Models\Proprietaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


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
                Tables\Columns\TextColumn::make('code_proprietaire')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('nom_proprietaire')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('prenom_proprietaire')->sortable()->searchable()->toggleable(),
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListProprietaires::route('/'),
            'create' => Pages\CreateProprietaire::route('/create'),
            'edit' => Pages\EditProprietaire::route('/{record}/edit'),
        ];
    }



}
