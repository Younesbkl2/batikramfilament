<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Projet;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProjetResource\Pages;
use App\Filament\App\Resources\ProjetResource\RelationManagers;


class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                Tables\Columns\TextColumn::make('codeprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Libelleprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('adresseprj')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('datedebuttrvx')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('datefintrvx')->date('d-m-Y')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('code_proprietaire')
                ->formatStateUsing(fn ($record) => "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})")
                ->sortable()->toggleable(),

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
                Filter::make('date_projet')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Date Début (A partir de)')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('created_until')
                            ->label('Date Fin (Jusqu à)')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('datedebuttrvx', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('datefintrvx', '<=', $date),
                            );
                    })
                    ->indicateUsing(fn (array $data) => array_filter([
                        isset($data['created_from']) && $data['created_from'] !== '' 
                            ? 'A partir de: ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y') 
                            : null,
                        isset($data['created_until']) && $data['created_until'] !== '' 
                            ? 'Jusqu à: ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y') 
                            : null,
                    ])),            
                Tables\Filters\SelectFilter::make('code_proprietaire')
                    ->label('Propriétaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(),                                                                                  

            ])
            ->recordUrl(null)
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
            'index' => Pages\ListProjets::route('/'),
            'create' => Pages\CreateProjet::route('/create'),
            'edit' => Pages\EditProjet::route('/{record}/edit'),
        ];
    }



}
