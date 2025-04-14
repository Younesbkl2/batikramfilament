<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PaiementResource\Pages;
use App\Filament\App\Resources\PaiementResource\RelationManagers;
use App\Models\Paiement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Tables';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('modepaie')
                ->options([
                    'Crédit Bancaire' => 'Crédit Bancaire',
                    'Espece' => 'Espece',
                    'Versement' => 'Versement',
                    'Remboursement' => 'Remboursement',
                ]),
                Forms\Components\Select::make('codebanque')
                ->searchable()
                ->preload()
                ->relationship('banque','codebanque')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque})"),
                Forms\Components\TextInput::make('montantpaie')->numeric()->inputMode('decimal'),
                Forms\Components\DatePicker::make('datepaie')
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),
                Forms\Components\Select::make('codeclient')
                ->searchable()
                ->preload()
                ->relationship('client','codeclient'),
                Forms\Components\Select::make('codachat')
                ->searchable()
                ->preload()
                ->relationship('achat','codachat'),

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
                Tables\Columns\TextColumn::make('codepaie')->sortable(),
                Tables\Columns\TextColumn::make('modepaie')->sortable(),
                Tables\Columns\TextColumn::make('codebanque')
                ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque})")
                ->sortable(),
                Tables\Columns\TextColumn::make('montantpaie')
                ->money('DZD')
                ->sortable(),
                Tables\Columns\TextColumn::make('datepaie')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('codeclient')->sortable(),
                Tables\Columns\TextColumn::make('codachat')->sortable(),
            ])
            ->filters([

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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
        ];
    }



}
