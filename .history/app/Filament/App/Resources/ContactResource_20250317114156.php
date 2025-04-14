<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ContactResource\Pages;
use App\Filament\App\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                Forms\Components\Textarea::make('OBS'),

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
                Tables\Columns\TextColumn::make('id')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ1')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ2')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ3')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ4')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ5')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ6')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Champ7')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('OBS')->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }



}
