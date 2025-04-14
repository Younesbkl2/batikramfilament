<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EtatPaiementResource\Pages;
use App\Filament\Resources\EtatPaiementResource\RelationManagers;
use App\Models\EtatPaiement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class EtatPaiementResource extends Resource
{
    protected static ?string $model = EtatPaiement::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Financial Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('codachat')
            ->deferLoading() // Add this for better performance
            ->columns([
                TextColumn::make('codachat')
                    ->label('Achat ID')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('codeclient')
                    ->label('Client Code')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($record) => $record->client->nomclient . ' ' . $record->client->prenomclient),
                
                // Appartement Section
                TextColumn::make('numappartement')
                    ->label('Appartement')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prix_appartement')
                    ->label('Appartement Price')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                
                // Parking Section
                TextColumn::make('numparking')
                    ->label('Parking')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prix_parking')
                    ->label('Parking Price')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                
                // New Local Section
                TextColumn::make('Numlocal')
                    ->label('Local Number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prix_local')
                    ->label('Local Price')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                
                // Financial Summary
                TextColumn::make('total_prix')
                    ->label('Total Price (A+P+L)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color('warning'),
                TextColumn::make('total_depense')
                    ->label('Amount Paid')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('reste_a_payer')
                    ->label('Remaining Balance')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($record) => $record->reste_a_payer > 0 ? 'danger' : 'success'),
                TextColumn::make('statue_paiment')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Payé' => 'success',
                        'PAS Payé' => 'danger',
                    }),
                
                // Project and Owner
                TextColumn::make('projet.Libelleprj')
                    ->label('Project')
                    ->sortable(),
                TextColumn::make('proprietaire.nom_proprietaire')
                    ->label('Owner')
                    ->formatStateUsing(fn ($record) => $record->proprietaire->nom_proprietaire . ' ' . $record->proprietaire->prenom_proprietaire),
            ])
            ->filters([
                SelectFilter::make('statue_paiment')
                    ->options([
                        'Payé' => 'Paid',
                        'PAS Payé' => 'Unpaid',
                    ]),
                
                SelectFilter::make('codeclient')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                    ->preload(),
                
                SelectFilter::make('codeprj')
                    ->relationship('projet', 'codeprj')
                    ->label('Project')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj})")
                    ->preload(),

                SelectFilter::make('code_proprietaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->label('Propriétaire')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Disable bulk actions as this is a view
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
            'index' => Pages\ListEtatPaiements::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'codeclient', 
            'numappartement', 
            'numparking',
            'Numlocal',  // Added local number to search
            'client.nomclient', 
            'client.prenomclient', 
            'projet.Libelleprj'
        ];
    }

}
