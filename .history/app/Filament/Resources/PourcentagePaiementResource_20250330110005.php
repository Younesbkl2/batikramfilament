<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\PourcentagePaiement;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PourcentagePaiementResource\Pages;
use App\Filament\Resources\PourcentagePaiementResource\RelationManagers;

class PourcentagePaiementResource extends Resource
{
    protected static ?string $model = PourcentagePaiement::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Rapports financiers';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Détails de la transaction')
                    ->schema([
                        TextInput::make('codachat')->disabled(),
                        TextInput::make('codeclient')->disabled(),
                        TextInput::make('nomclient')->disabled(),
                        TextInput::make('prenomclient')->disabled(),
                    ])->columns(2),
                
                Section::make('Détails financiers')
                    ->schema([
                        TextInput::make('prix_total')->numeric()->disabled(),
                        TextInput::make('apport_personel')->numeric()->disabled(),
                        TextInput::make('credit_bancaire')->numeric()->disabled(),
                        TextInput::make('totalite_paiements')->numeric()->disabled(),
                    ])->columns(2),
                
                Section::make('Pourcentages de paiement')
                    ->schema([
                        TextInput::make('percentage_apport_personel_paye')
                            ->suffix('%')->disabled(),
                        TextInput::make('percentage_credit_bancaire_paye')
                            ->suffix('%')->disabled(),
                        TextInput::make('percentage_total_paye')
                            ->suffix('%')->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codachat')->sortable()->searchable(),
                TextColumn::make('nomclient')->sortable(),
                TextColumn::make('prenomclient')->sortable(),
                
                // Financial amounts
                TextColumn::make('prix_total')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('apport_personel')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('credit_bancaire')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('totalite_paiements')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                // Percentage columns
                TextColumn::make('percentage_apport_personel_paye')
                    ->suffix('%')
                    ->color(fn ($record) => $record->percentage_apport_personel_paye >= 100 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('percentage_credit_bancaire_paye')
                    ->suffix('%')
                    ->color(fn ($record) => $record->percentage_credit_bancaire_paye >= 100 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('percentage_total_paye')
                    ->suffix('%')
                    ->color(fn ($record) => $record->percentage_total_paye >= 100 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('codeclient')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('codebanque')
                    ->relationship('banque', 'codebanque')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
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
            'index' => Pages\ListPourcentagePaiements::route('/'),
        ];
    }
}
