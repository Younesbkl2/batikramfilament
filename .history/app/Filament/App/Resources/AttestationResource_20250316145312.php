<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AttestationResource\Pages;
use App\Filament\App\Resources\AttestationResource\RelationManagers;
use App\Models\Attestation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


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
                Tables\Columns\TextColumn::make('ID_ATTESTATION')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('reservation')
                ->boolean()
                ->sortable(), 
                Tables\Columns\IconColumn::make('reservation_notaire')
                ->boolean()
                ->sortable(),
                Tables\Columns\IconColumn::make('prestation')
                ->boolean()
                ->sortable(),
                Tables\Columns\IconColumn::make('remise_des_clés')
                ->boolean()
                ->sortable(),
                Tables\Columns\TextColumn::make('Num_attestation')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('codachat')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_attestation')->date('d-m-Y')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('OBS'),
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
                Filter::make('date_attestation')
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
            'index' => Pages\ListAttestations::route('/'),
            'create' => Pages\CreateAttestation::route('/create'),
            'edit' => Pages\EditAttestation::route('/{record}/edit'),
        ];
    }



}
