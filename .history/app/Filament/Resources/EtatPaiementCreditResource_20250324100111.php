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
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtatPaiementCreditResource\Pages;
use App\Filament\Resources\EtatPaiementCreditResource\RelationManagers;

class EtatPaiementCreditResource extends Resource
{
    protected static ?string $model = EtatPaiementCredit::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
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
                        TextInput::make('codeclient')
                            ->label('Client')
                            ->formatStateUsing(fn ($record) => $record->client->nomclient.' '.$record->client->prenomclient)
                            ->disabled(),
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
                        TextInput::make('projet.Libelleprj')->label('Projet')->disabled(),
                        TextInput::make('proprietaire.full_name')
                            ->label('Propriétaire')
                            ->formatStateUsing(fn ($record) => 
                                $record->proprietaire->nom_proprietaire.' '.$record->proprietaire->prenom_proprietaire)
                            ->disabled(),
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
                    ->toggleable()
                    ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})"),
                
                TextColumn::make('Numdetel')  // Added Numdetel
                    ->label('Tél 1')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('NUM_TEL')
                    ->label('Tél 2')
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
                    ->toggleable(),
                
                TextColumn::make('proprietaire.full_name')
                    ->label('Propriétaire')
                    ->formatStateUsing(fn ($record) => 
                        $record->proprietaire->nom_proprietaire.' '.$record->proprietaire->prenom_proprietaire)
                    ->toggleable(),
                
                TextColumn::make('Observations')
                    ->label('Notes')
                    ->wrap()
                    ->toggleable()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => "Crédit - Achat #{$record->codachat}"),
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
            'index' => Pages\ListEtatPaiementCredits::route('/'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['client', 'projet', 'proprietaire']);
    }

}
