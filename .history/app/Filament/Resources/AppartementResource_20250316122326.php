<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppartementResource\Pages;
use App\Filament\Resources\AppartementResource\RelationManagers;
use App\Models\Appartement;
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


class AppartementResource extends Resource
{
    protected static ?string $model = Appartement::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
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
                Forms\Components\TextInput::make('numappartement')->required(),
                Forms\Components\TextInput::make('blocappartement'),
                Forms\Components\TextInput::make('superficie')->numeric()->inputMode('decimal'),
                Forms\Components\TextInput::make('etage'),
                Forms\Components\Select::make('coteappartement')
                ->options([
                    'Gauche' => 'Gauche',
                    'Droite' => 'Droite',
                ]),
                Forms\Components\Select::make('codeprod')
                ->searchable()
                ->preload()
                ->relationship('produit','codeprod')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})"),
                Forms\Components\TextInput::make('prixdelogt')->numeric()->inputMode('decimal'),
                Forms\Components\Select::make('codeprj')
                ->searchable()
                ->preload()
                ->relationship('projet','codeprj')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj})"),
                Forms\Components\Select::make('code_proprietaire')
                ->searchable()
                ->preload()
                ->relationship('proprietaire','code_proprietaire')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})"),
                Forms\Components\TextInput::make('NumEDD'),
                Forms\Components\TextInput::make('Numpiece'),
                Forms\Components\Textarea::make('obs'),
                Forms\Components\Checkbox::make('reservation')->default(false),

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
                Tables\Columns\TextColumn::make('numappartement')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('blocappartement')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('superficie')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('etage')->sortable(),
                Tables\Columns\TextColumn::make('coteappartement')->sortable(),
                Tables\Columns\TextColumn::make('codeprod')
                ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})")
                ->sortable()->searchable(),
                Tables\Columns\IconColumn::make('reservation')
                ->boolean()
                ->sortable(),
                Tables\Columns\TextColumn::make('prixdelogt')
                ->money('DZD')
                ->sortable(),
                Tables\Columns\TextColumn::make('codeprj')
                ->formatStateUsing(fn ($record) => "{$record->codeprj} ({$record->projet->Libelleprj})")
                ->sortable(),
                Tables\Columns\TextColumn::make('code_proprietaire')
                ->formatStateUsing(fn ($record) => "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})")
                ->sortable(),
                Tables\Columns\TextColumn::make('NumEDD')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Numpiece')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('obs'),

            ])
            ->filters([
                Tables\Filters\TextFilter::make('blocappartement')
                ->label('Bloc Appartement')
                ->query(fn (Builder $query, string $value) => $query->whereRaw('LOWER(blocappartement) LIKE ?', ['%' . strtolower($value) . '%'])),

            Tables\Filters\TextFilter::make('etage')
                ->label('Étage')
                ->query(fn (Builder $query, string $value) => $query->whereRaw('LOWER(etage) LIKE ?', ['%' . strtolower($value) . '%'])),
            
                Tables\Filters\SelectFilter::make('coteappartement')
                    ->label('Côté Appartement')
                    ->options([
                        'Gauche' => 'Gauche',
                        'Droite' => 'Droite',
                    ])
                    ->searchable()
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('codeprod')
                    ->label('Produit')
                    ->relationship('produit', 'codeprod')
                    ->searchable()
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('codeprj')
                    ->label('Projet')
                    ->relationship('projet', 'codeprj')
                    ->searchable()
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('code_proprietaire')
                    ->label('Propriétaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('reservation')
                    ->label('Réservé')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->searchable()
                    ->preload(),
            
                TrashedFilter::make(), // For soft deletes
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
            'index' => Pages\ListAppartements::route('/'),
            'create' => Pages\CreateAppartement::route('/create'),
            'edit' => Pages\EditAppartement::route('/{record}/edit'),
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
