<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use App\Filament\Exports\ContactExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Resources\ContactResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactResource\RelationManagers;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
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
                Forms\Components\TextInput::make('Champ1'),
                Forms\Components\TextInput::make('Champ2'),
                Forms\Components\TextInput::make('Champ3'),
                Forms\Components\TextInput::make('Champ4'),
                Forms\Components\TextInput::make('Champ5'),
                Forms\Components\TextInput::make('Champ6'),
                Forms\Components\TextInput::make('Champ7'),
                Forms\Components\Textarea::make('OBS')->label('OBS'),

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
                Tables\Columns\TextColumn::make('id')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ1')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ2')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ3')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ4')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ5')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ6')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ7')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('OBS')->label('OBS')->toggleable(),

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

                // Champ1 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ1')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 1')
                            ->placeholder('Enter le Champ 1')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ1) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 1: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ2 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ2')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 2')
                            ->placeholder('Enter le Champ 2')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ2) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 2: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ3 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ3')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 3')
                            ->placeholder('Enter le Champ 3')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ3) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 3: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ4 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ4')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 4')
                            ->placeholder('Enter le Champ 4')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ4) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 4: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ 5 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ5')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 5')
                            ->placeholder('Enter le Champ 5')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ5) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 5: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ 6 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ6')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 6')
                            ->placeholder('Enter le Champ 6')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ6) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 6: ' . $data['value'] 
                            : null
                    ),
                    
                // Champ 7 - Case-Insensitive Search with Active Filter Display
                Filter::make('Champ7')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Champ 7')
                            ->placeholder('Enter le Champ 7')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Champ7) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Champ 7: ' . $data['value'] 
                            : null
                    ),

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
            ->headerActions([
                ExportAction::make()->label('Exporter tous les Contacts')->exporter(ContactExporter::class),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make()->label('Exporter les Contacts sélectionnés')->exporter(ContactExporter::class)
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
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
