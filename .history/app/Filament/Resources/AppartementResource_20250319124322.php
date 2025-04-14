<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appartement;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppartementResource\Pages;
use App\Filament\Resources\AppartementResource\RelationManagers;


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
                Tables\Columns\TextColumn::make('numappartement')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('blocappartement')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('superficie')
                ->numeric()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('etage')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('coteappartement')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('codeprod')
                ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})")
                ->sortable()->searchable()->toggleable(),
                Tables\Columns\IconColumn::make('reservation')
                ->boolean()
                ->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('prixdelogt')
                ->money('DZD')
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('codeprj')
                ->formatStateUsing(fn ($record) => "{$record->codeprj} ({$record->projet->Libelleprj})")
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('code_proprietaire')
                ->formatStateUsing(fn ($record) => "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})")
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('NumEDD')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Numpiece')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('obs')->toggleable(),

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

                Tables\Filters\SelectFilter::make('numappartement')
                    ->label('Appartement')
                    ->options(fn () => Appartement::pluck('numappartement', 'numappartement')->toArray())
                    ->searchable()
                    ->preload(),                
                Tables\Filters\SelectFilter::make('blocappartement')
                    ->label('Bloc Appartement')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Appartement::pluck('blocappartement', 'blocappartement')->toArray()),
                Tables\Filters\SelectFilter::make('etage')
                    ->label('Etage')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Appartement::pluck('etage', 'etage')->toArray()),            
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
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})")
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('codeprj')
                    ->label('Projet')
                    ->relationship('projet', 'codeprj')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj})")
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('code_proprietaire')
                    ->label('Propriétaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('reservation')
                    ->label('Réservé')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),
            
                TrashedFilter::make(), // For soft deletes
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
