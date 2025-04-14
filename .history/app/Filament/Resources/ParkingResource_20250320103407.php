<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Parking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\ParkingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParkingResource\RelationManagers;

class ParkingResource extends Resource
{
    protected static ?string $model = Parking::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
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
                Forms\Components\TextInput::make('numparking')->required(),
                Forms\Components\TextInput::make('surfaceparking')->numeric()->inputMode('decimal'),
                Forms\Components\Select::make('codeprod')
                ->searchable()
                ->preload()
                ->relationship('produit','codeprod')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})"),
                Forms\Components\TextInput::make('prixparking')->numeric()->inputMode('decimal'),
                Forms\Components\Checkbox::make('reservationparking')->default(false),

                Forms\Components\Placeholder::make('spacer') // Acts as an empty space
                ->label(' '), // Empty label to keep spacing

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
                Tables\Columns\TextColumn::make('numparking')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('surfaceparking')
                ->numeric()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('codeprod')
                ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})")
                ->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('prixparking')
                ->money('DZD')
                ->sortable()
                ->toggleable(),
                Tables\Columns\IconColumn::make('reservationparking')
                ->boolean()
                ->sortable()
                ->toggleable(),

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

                Tables\Filters\SelectFilter::make('numparking')
                    ->label('Num Parking')
                    ->searchable()
                    ->options(fn () => Parking::pluck('numparking', 'numparking')->toArray())
                    ->preload(),
                Tables\Filters\SelectFilter::make('codeprod')
                    ->label('Produit')
                    ->relationship('produit', 'codeprod')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})")
                    ->preload(),
                Tables\Filters\SelectFilter::make('surfaceparking')
                    ->label('Surface')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Parking::whereNotNull('surfaceparking')->orderBy('surfaceparking')->pluck('surfaceparking', 'surfaceparking')->toArray()),                     
                Tables\Filters\SelectFilter::make('reservationparking')
                    ->label('Reservation Parking')
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
            'index' => Pages\ListParkings::route('/'),
            'create' => Pages\CreateParking::route('/create'),
            'edit' => Pages\EditParking::route('/{record}/edit'),
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
