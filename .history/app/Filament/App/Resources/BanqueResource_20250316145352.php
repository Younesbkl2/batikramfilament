<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BanqueResource\Pages;
use App\Filament\App\Resources\BanqueResource\RelationManagers;
use App\Models\Banque;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


class BanqueResource extends Resource
{
    protected static ?string $model = Banque::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomdebanque'),
                Forms\Components\TextInput::make('adressedebanque'),
                Forms\Components\TextInput::make('numdecompte'),

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
                Tables\Columns\TextColumn::make('codebanque')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nomdebanque')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('adressedebanque')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('numdecompte')->sortable()->searchable(),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('nomdebanque')
                    ->label('Nom de la Banque')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Banque::pluck('nomdebanque', 'nomdebanque')->toArray()),
                // adressedebanque - Case-Insensitive Search with Active Filter Display
                Filter::make('adressedebanque')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Adresse de la Banque')
                            ->placeholder('Enter Adresse de la Banque')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(adressedebanque) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Adresse de la Banque: ' . $data['value'] 
                            : null
                    ),
                // numdecompte - Case-Insensitive Search with Active Filter Display
                Filter::make('numdecompte')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Num de Compte')
                            ->placeholder('Enter Num de Compte')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(numdecompte) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Num de Compte: ' . $data['value'] 
                            : null
                    ),                                                        

                TrashedFilter::make(),
            ])
            ->actions([
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
            'index' => Pages\ListBanques::route('/'),
            'create' => Pages\CreateBanque::route('/create'),
            'edit' => Pages\EditBanque::route('/{record}/edit'),
        ];
    }



}
