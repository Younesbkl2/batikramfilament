<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\PourcentagePaiement;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PourcentagePaiementResource\Pages;
use App\Filament\Resources\PourcentagePaiementResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;

class PourcentagePaiementResource extends Resource
{
    protected static ?string $model = PourcentagePaiement::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Rapports financiers';

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
        $details['Banque'] = $record->banque->nomdebanque . ' ' . $record->banque->addresedebanque;
        
        if (!empty($record->codachat)) {
            $details['Achat'] = $record->codachat;
        }
        if (!empty($record->numappartement)) {
            $details['Appartement'] = $record->numappartement;
        }
        if (!empty($record->numparking)) {
            $details['Parking'] = $record->numparking;
        }
        if (!empty($record->Numlocal)) {
            $details['Local'] = $record->Numlocal;
        }
        
        $details['% Apport Personel Payé'] = $record->percentage_apport_personel_paye->suffix('%');
        $details['% Crédit Bancaire Payé'] = $record->percentage_credit_bancaire_paye->suffix('%');
        $details['% Total Payé'] = $record->percentage_total_paye->suffix('%');
        
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
                            ->disabled(),
                        TextInput::make('nomclient')->label('Nom Client')->disabled(),
                        TextInput::make('prenomclient')->label('Prénom Client')->disabled(),
                        Select::make('codebanque')
                        ->searchable()
                        ->preload()
                        ->relationship('banque','codebanque')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque} {$record->adressedebanque})")
                        ->label('Banque')
                        ->disabled(),
                    ])->columns(2),

                    Section::make('Détails de la propriété')
                    ->schema([
                        TextInput::make('numappartement')->label('Appartement')->disabled(),
                        TextInput::make('prixdelogt')->label('Prix Appartement')->numeric()->disabled(),
                        TextInput::make('numparking')->label('Parking')->disabled(),
                        TextInput::make('prixparking')->label('Prix Parking')->numeric()->disabled(),
                        TextInput::make('Numlocal')->label('Local')->disabled(),
                        TextInput::make('prixlocal')->label('Prix Local')->numeric()->disabled(),
                    ])
                    ->columns(2),                   

                Section::make('Détails financiers')
                    ->schema([
                        TextInput::make('prix_total')->label('Prix Total')->numeric()->disabled(),
                        TextInput::make('apport_personel')->label('Apport Personel')->numeric()->disabled(),
                        TextInput::make('credit_bancaire')->label('Crédit Bancaire')->numeric()->disabled(),
                        TextInput::make('apport_personel_paye')->label('Apport Personel Payé')->numeric()->disabled(),
                        TextInput::make('credit_bancaire_paye')->label('Crédit Bancaire Payé')->numeric()->disabled(),
                        TextInput::make('totalite_paiements')->label('Totalité Paiements')->numeric()->disabled(),
                    ])->columns(3),
                
                Section::make('Pourcentages de paiement')
                    ->schema([
                        TextInput::make('percentage_apport_personel_paye')
                        ->label('% Apport Personel Payé')->suffix('%')->disabled(),
                        TextInput::make('percentage_credit_bancaire_paye')
                        ->label('% Crédit Bancaire Payé')->suffix('%')->disabled(),
                        TextInput::make('percentage_total_paye')
                        ->label('% Total Payé')->suffix('%')->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codachat')
                    ->label('Achat ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('codeclient')
                    ->label('Code Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('nomclient')
                    ->label('Nom Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('prenomclient')
                    ->label('Prénom Client')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
             
                TextColumn::make('codebanque')
                    ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})")
                    ->label('Banque')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Appartement Section
                TextColumn::make('numappartement')
                    ->label('Appartement')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('prixdelogt')
                    ->label('Prix Appartement')
                    ->numeric(decimalPlaces: 2)
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                // Parking Section
                TextColumn::make('numparking')
                    ->label('Parking')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('prixparking')
                    ->label('Prix Parking')
                    ->numeric(decimalPlaces: 2)
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                // New Local Section
                TextColumn::make('Numlocal')
                    ->label('Local')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('prixlocal')
                    ->label('Prix Local')
                    ->numeric(decimalPlaces: 2)
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),

                // Financial amounts
                TextColumn::make('prix_total')
                    ->label('Prix Total')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('apport_personel')
                    ->label('Apport Personel')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('apport_personel_paye')
                    ->label('Apport Personel Payé')                
                    ->money('DZD')
                    ->sortable()
                    ->toggleable()
                    ->color('warning'),

                TextColumn::make('percentage_apport_personel_paye')
                    ->label('% Apport Personel Payé')                
                    ->suffix('%')
                    ->color(fn ($record) => $record->percentage_apport_personel_paye >= 100 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('credit_bancaire')
                    ->label('Crédit Bancaire')
                    ->money('DZD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('credit_bancaire_paye')
                    ->label('Crédit Bancaire Payé')                
                    ->money('DZD')
                    ->sortable()
                    ->toggleable()
                    ->color('warning'),

                TextColumn::make('percentage_credit_bancaire_paye')
                    ->label('% Crédit Bancaire Payé')                
                    ->suffix('%')
                    ->color(fn ($record) => $record->percentage_credit_bancaire_paye >= 100 ? 'success' : 'danger')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('totalite_paiements')
                    ->label('Totalité Paiements')                
                    ->money('DZD')
                    ->sortable()
                    ->toggleable()
                    ->color('warning'),
                    
                TextColumn::make('percentage_total_paye')
                    ->label('% Total Payé')                
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
                Tables\Actions\ViewAction::make()
                ->modalHeading(fn ($record) => "Pourcentage Paiement - Achat #{$record->codachat}"),
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

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['achat', 'banque', 'appartement', 'parking', 'local', 'client']);
    }
}
