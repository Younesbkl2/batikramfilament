<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\EtatPaiementCredit;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\EtatPaiementCreditExporter;
use App\Filament\Resources\EtatPaiementCreditResource\Pages;
use App\Filament\Resources\EtatPaiementCreditResource\RelationManagers;

class EtatPaiementCreditResource extends Resource
{
    protected static ?string $model = EtatPaiementCredit::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Rapports financiers';

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
            'codachat',
            'codeclient', 
            'client.nomclient', 
            'client.prenomclient'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];
        
        $details['Code Client'] = $record->client->codeclient;
        $details['Client'] = $record->client->nomclient . ' ' . $record->client->prenomclient;

        if (!empty($record->codachat)) {
            $details['Achat'] = $record->codachat;
        }
        if (!empty($record->numappartement)) {
            $details['Appartement'] = $record->numappartement;
        }
        if (!empty($record->projet->Libelleprj)) {
            $details['Projet'] ="{$record->projet->Libelleprj} ({$record->projet->adresseprj})";
        }
        if (!empty($record->proprietaire->nom_proprietaire)) {
            $details['Proprietaire'] = $record->proprietaire->nom_proprietaire . ' ' . $record->proprietaire->prenom_proprietaire;
        }
        return $details;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Détails de la transaction')
                    ->schema([
                        TextInput::make('codachat')->label('Achat ID')->disabled(),
                        TextInput::make('codeclient')->label('Code Client')->disabled(),
                        TextInput::make('nomclient')->label('Nom Client')->disabled(),
                        TextInput::make('prenomclient')->label('Prénom Client')->disabled(),
                    ])
                    ->columns(2),
                
                Section::make('Coordonnées client')
                    ->schema([
                        TextInput::make('Numdetel')->label('Numéro de téléphone 1')->disabled(),
                        TextInput::make('NUM_TEL')->label('Numéro de téléphone 2')->disabled(),
                    ])
                    ->columns(2),
                
                Section::make('Détails propriété')
                    ->schema([
                        TextInput::make('numappartement')->label('Appartement')->disabled(),
                        Forms\Components\Select::make('a.codeprj')
                        ->searchable()
                        ->preload()
                        ->relationship('projet','a.codeprj')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj} {$record->adresseprj})"),
                        Forms\Components\Select::make('a.code_proprietaire')
                        ->searchable()
                        ->preload()
                        ->relationship('proprietaire','a.code_proprietaire')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})"),
                    ])
                    ->columns(3),
                
                Section::make('Observations')
                    ->schema([
                        Textarea::make('Observations')->disabled(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codachat')  // Added as first column
                    ->label('Achat ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('codeclient')
                    ->label('Code Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('client.nomclient')
                    ->label('Nom Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('client.prenomclient')
                    ->label('Prénom Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('Numdetel')  // Added Numdetel
                    ->label('Numdetel')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('NUM_TEL')
                    ->label('NUM_TEL')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('numappartement')
                    ->label('Appartement')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('projet.Libelleprj')
                    ->label('Projet')
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn ($record) => $record->projet->Libelleprj . ' ' . $record->projet->adresseprj),
                TextColumn::make('proprietaire.nom_proprietaire')
                    ->label('Propriétaire')
                    ->toggleable()
                    ->formatStateUsing(fn ($record) => $record->proprietaire->nom_proprietaire . ' ' . $record->proprietaire->prenom_proprietaire),
                
                TextColumn::make('Observations')
                    ->label('Observation')
                    ->wrap()
                    ->toggleable()
            ])
            ->filters([
                SelectFilter::make('codeclient')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->label('Code Client')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                    ->preload(),
                
                SelectFilter::make('client.nomclient')
                    ->relationship('client', 'nomclient')
                    ->searchable()
                    ->label('Nom Client')
                    ->preload(),
                
                SelectFilter::make('client.prenomclient')
                    ->relationship('client', 'prenomclient')
                    ->searchable()
                    ->label('Prénom Client')
                    ->preload(),
                
                SelectFilter::make('projet')
                    ->relationship('projet', 'codeprj')
                    ->searchable()
                    ->label('Projet')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprj} ({$record->Libelleprj} {$record->adresseprj})")
                    ->preload(),
                
                SelectFilter::make('proprietaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->label('Propriétaire')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => "Crédit - Achat #{$record->codachat}"),
            ])
            ->headerActions([
                ExportAction::make()->label('Exporter tous les Etat Paiement Credits')->exporter(EtatPaiementCreditExporter::class),
            ])
            ->bulkActions([
                ExportBulkAction::make()->label('Exporter les Etat Paiement Credits sélectionnés')->exporter(EtatPaiementCreditExporter::class)
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
            'index' => Pages\ListEtatPaiementCredits::route('/'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['client', 'achat', 'appartement', 'projet', 'proprietaire']);
    }

}
