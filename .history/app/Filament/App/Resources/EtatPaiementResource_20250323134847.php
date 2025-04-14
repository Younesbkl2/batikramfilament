<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EtatPaiement;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\EtatPaiementResource\Pages;
use App\Filament\App\Resources\EtatPaiementResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;

class EtatPaiementResource extends Resource
{
    protected static ?string $model = EtatPaiement::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';
    protected static ?string $navigationGroup = 'Financial Reports';

    // Disable create button
    public static function canCreate(): bool
   {
      return false;
   }



    // Add these properties and methods at the top of your resource
    protected static ?string $recordTitleAttribute = 'codachat';

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

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];
        
        $details['Client'] = $record->client->nomclient . ' ' . $record->client->prenomclient;
        
        if (!empty($record->numappartement)) {
            $details['Appartement'] = $record->numappartement;
        }
        if (!empty($record->numparking)) {
            $details['Parking'] = $record->numparking;
        }
        if (!empty($record->Numlocal)) {
            $details['Local'] = $record->Numlocal;
        }
        
        $details['Statut'] = $record->statue_paiment;
        
        return $details;
    }

   public static function form(Form $form): Form
   {
       return $form
           ->schema([
               Section::make('Détails de la transaction')
                   ->schema([
                       TextInput::make('codachat')->label('Achat ID')->disabled(),
                       TextInput::make('codeclient')
                           ->label('Client')
                           ->formatStateUsing(fn ($record) => $record->client->nomclient.' '.$record->client->prenomclient)
                           ->disabled(),
                   ])
                   ->columns(2),
               
               Section::make('Détails de la propriété')
                   ->schema([
                       TextInput::make('numappartement')->label('Appartement')->disabled(),
                       TextInput::make('prix_appartement')->label('Prix')->numeric()->disabled(),
                       TextInput::make('numparking')->label('Parking')->disabled(),
                       TextInput::make('prix_parking')->label('Prix')->numeric()->disabled(),
                       TextInput::make('Numlocal')->label('Local')->disabled(),
                       TextInput::make('prix_local')->label('Prix')->numeric()->disabled(),
                   ])
                   ->columns(2),
               
               Section::make('Résumé financier')
                   ->schema([
                       TextInput::make('total_prix')->label('Prix total')->numeric()->disabled(),
                       TextInput::make('total_depense')->label('Montant payé')->numeric()->disabled(),
                       TextInput::make('reste_a_payer')->label('Montant restant')->numeric()->disabled(),
                       TextInput::make('statue_paiment')->label('Statut de paiement')->disabled(),
                   ])
                   ->columns(3),
               
               Section::make('Projet et propriétaire')
                   ->schema([
                       Forms\Components\Select::make('a.codeprj')
                       ->searchable()
                       ->preload()
                       ->relationship('projet','a.codeprj')
                       ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj})"),
                       Forms\Components\Select::make('a.code_proprietaire')
                       ->searchable()
                       ->preload()
                       ->relationship('proprietaire','a.code_proprietaire')
                       ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})"),
                   ])
                   ->columns(2),
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
                   ->searchable()
                   ->toggleable(),
               
               TextColumn::make('codeclient')
                   ->label('Client Code')
                   ->sortable()
                   ->searchable()
                   ->toggleable()
                   ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})"),
               
               // Appartement Section
               TextColumn::make('numappartement')
                   ->label('Appartement')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),
               TextColumn::make('prix_appartement')
                   ->label('Prix Appartement')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable(),
               
               // Parking Section
               TextColumn::make('numparking')
                   ->label('Parking')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),
               TextColumn::make('prix_parking')
                   ->label('Prix Parking')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable(),
               
               // New Local Section
               TextColumn::make('Numlocal')
                   ->label('Local')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),
               TextColumn::make('prix_local')
                   ->label('Prix Local')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable(),
               
               // Financial Summary
               TextColumn::make('total_prix')
                   ->label('Prix total')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable()
                   ->color('warning'),
               TextColumn::make('total_depense')
                   ->label('Montant payé')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable(),
               TextColumn::make('reste_a_payer')
                   ->label('Montant restant')
                   ->numeric(decimalPlaces: 2)
                   ->sortable()
                   ->toggleable()
                   ->color(fn ($record) => $record->reste_a_payer > 0 ? 'danger' : 'success'),
               TextColumn::make('statue_paiment')
                   ->label('Statut de paiement')
                   ->badge()
                   ->toggleable()
                   ->color(fn (string $state): string => match ($state) {
                       'Payé' => 'success',
                       'PAS Payé' => 'danger',
                   }),
               
               // Project and Owner
               TextColumn::make('projet.Libelleprj')
                   ->label('Projet')
                   ->sortable()
                   ->toggleable(),
               TextColumn::make('proprietaire.nom_proprietaire')
                   ->label('Propriétaire')
                   ->toggleable()
                   ->formatStateUsing(fn ($record) => $record->proprietaire->nom_proprietaire . ' ' . $record->proprietaire->prenom_proprietaire),
           ])
           ->filters([
               SelectFilter::make('statue_paiment')
                   ->options([
                        'Payé' => 'Payé',
                        'PAS Payé' => 'PAS Payé',
                   ]),
               
               SelectFilter::make('codeclient')
                   ->relationship('client', 'codeclient')
                   ->searchable()
                   ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                   ->preload(),
               
               SelectFilter::make('codeprj')
                   ->relationship('projet', 'codeprj')
                   ->label('Projet')
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
           ->recordUrl(null)
           ->actions([
               Tables\Actions\ViewAction::make()
                   ->modalHeading(fn ($record) => "Détails de paiement - Achat #{$record->codachat}"),
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


}
