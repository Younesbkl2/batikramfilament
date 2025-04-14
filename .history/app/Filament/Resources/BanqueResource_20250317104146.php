<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BanqueResource\Pages;
use App\Filament\Resources\BanqueResource\RelationManagers;
use App\Models\Banque;
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

class BanqueResource extends Resource
{
    protected static ?string $model = Banque::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Tables';

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
                Tables\Columns\TextColumn::make('codebanque')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('nomdebanque')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('adressedebanque')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('numdecompte')->sortable()->searchable()->toggleable(),
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
            'index' => Pages\ListBanques::route('/'),
            'create' => Pages\CreateBanque::route('/create'),
            'edit' => Pages\EditBanque::route('/{record}/edit'),
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
