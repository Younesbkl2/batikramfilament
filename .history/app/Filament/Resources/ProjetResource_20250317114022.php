<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjetResource\Pages;
use App\Filament\Resources\ProjetResource\RelationManagers;
use App\Models\Projet;
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

class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
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
                Forms\Components\TextInput::make('Libelleprj'),
                Forms\Components\TextInput::make('adresseprj'),
                Forms\Components\DatePicker::make('datedebuttrvx')
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),  
                Forms\Components\DatePicker::make('datefintrvx')
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('code_proprietaire')
                ->searchable()
                ->preload()
                ->relationship('proprietaire','code_proprietaire')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})"),

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
                Tables\Columns\TextColumn::make('codeprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Libelleprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('adresseprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('datedebuttrvx')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('datefintrvx')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('code_proprietaire')
                ->formatStateUsing(fn ($record) => "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})")
                ->sortable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),   

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('Libelleprj')
                ->label('Libelle du Projet')
                ->searchable()
                ->preload()
                ->options(fn () => Projet::pluck('Libelleprj', 'Libelleprj')->toArray()),
                // adresseprj - Case-Insensitive Search with Active Filter Display
                Filter::make('adresseprj')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Adresse du Projet')
                            ->placeholder('Enter Adresse du projet')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(adresseprj) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Adresse du Projet: ' . $data['value'] 
                            : null
                    ),
                Filter::make('datedebuttrvx')
                    ->form([
                        Forms\Components\DatePicker::make('value')
                            ->label('Date de Debut du Projet')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereDate('datedebuttrvx', '=', $data['value'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Date de Debut du Projet: ' . \Carbon\Carbon::parse($data['value'])->format('d/m/Y')
                            : null
                    ),
                Filter::make('datefintrvx')
                    ->form([
                        Forms\Components\DatePicker::make('value')
                            ->label('Date de Fin du Projet')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereDate('datefintrvx', '=', $data['value'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Date de Fin du Projet: ' . \Carbon\Carbon::parse($data['value'])->format('d/m/Y')
                            : null
                    ),
                Tables\Filters\SelectFilter::make('code_proprietaire')
                    ->label('Propriétaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(),                                                                                  

                TrashedFilter::make(),
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
            'index' => Pages\ListProjets::route('/'),
            'create' => Pages\CreateProjet::route('/create'),
            'edit' => Pages\EditProjet::route('/{record}/edit'),
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
