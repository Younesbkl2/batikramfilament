<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Attestation;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\AttestationResource\Pages;
use App\Filament\App\Resources\AttestationResource\RelationManagers;


class AttestationResource extends Resource
{
    protected static ?string $model = Attestation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('reservation')->default(false),
                Forms\Components\Checkbox::make('reservation_notaire')->default(false),
                Forms\Components\Checkbox::make('prestation')->default(false),
                Forms\Components\Checkbox::make('remise_des_clés')->default(false),
                Forms\Components\TextInput::make('Num_attestation'),
                Forms\Components\Select::make('codachat')
                ->searchable()
                ->preload()
                ->relationship('achat','codachat'),
                Forms\Components\DatePicker::make('date_attestation')
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),
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
                Tables\Columns\TextColumn::make('ID_ATTESTATION')->label('ID_ATTESTATION')->sortable()->searchable()->toggleable(),
                Tables\Columns\IconColumn::make('reservation')
                ->boolean()
                ->sortable()
                ->toggleable(), 
                Tables\Columns\IconColumn::make('reservation_notaire')
                ->boolean()
                ->sortable()
                ->toggleable(),
                Tables\Columns\IconColumn::make('prestation')
                ->boolean()
                ->sortable()
                ->toggleable(),
                Tables\Columns\IconColumn::make('remise_des_clés')
                ->boolean()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('Num_attestation')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codachat')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('date_attestation')->date('d-m-Y')->sortable()->searchable()->toggleable(),
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

                Tables\Columns\TextColumn::make('lastModifiedBy.name')
                    ->label('Dernière modification par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('reservation')
                    ->label('Réservé')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),
                Tables\Filters\SelectFilter::make('reservation_notaire')
                    ->label('Notaire Réservé')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),
                Tables\Filters\SelectFilter::make('prestation')
                    ->label('Préstation')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),
                Tables\Filters\SelectFilter::make('remise_des_clés')
                    ->label('Remise des clés')
                    ->options([
                        '1' => 'Oui',
                        '0' => 'Non',
                    ])
                    ->preload(),                                                            
                // Num_attestation - Case-Insensitive Search with Active Filter Display
                Filter::make('Num_attestation')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('value')
                            ->label('Num Attestation')
                            ->placeholder('Enter Num Attestation')
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereRaw('LOWER(Num_attestation) LIKE ?', ['%' . strtolower($data['value']) . '%'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Num Attestation: ' . $data['value'] 
                            : null
                    ),
                Tables\Filters\SelectFilter::make('codachat')
                    ->label('Code Achat')
                    ->relationship('achat', 'codachat')
                    ->searchable()
                    ->preload(),                    
                Filter::make('date_attestation1')
                    ->form([
                        Forms\Components\DatePicker::make('value')
                            ->label('Date Attestation')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn (Builder $query, array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereDate('date_attestation', '=', $data['value'])
                            : $query
                    )
                    ->indicateUsing(fn (array $data) => 
                        isset($data['value']) && $data['value'] !== ''
                            ? 'Date Attestation: ' . \Carbon\Carbon::parse($data['value'])->format('d/m/Y')
                            : null
                    ),                                        
                Filter::make('date_attestation2')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('A partir de')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('created_until')
                            ->label('Jusqu à')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date_attestation', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date_attestation', '<=', $date),
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
            'index' => Pages\ListAttestations::route('/'),
            'create' => Pages\CreateAttestation::route('/create'),
            'edit' => Pages\EditAttestation::route('/{record}/edit'),
        ];
    }



}
