<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use App\Models\PaiementsDesProprietaires;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PaiementsDesProprietairesResource\Pages;
use App\Filament\App\Resources\PaiementsDesProprietairesResource\RelationManagers;

class PaiementsDesProprietairesResource extends Resource
{
    protected static ?string $model = PaiementsDesProprietaires::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-yen';
    protected static ?string $navigationGroup = 'Rapports financiers';

    // Disable create button
    public static function canCreate(): bool
   {
      return false;
   }

   public static function form(Form $form): Form
   {
       return $form
           ->schema([
               Forms\Components\Section::make('Détails de paiement')
                   ->schema([
                       Forms\Components\TextInput::make('codepaie')->label('Code Paiement')->disabled(),
                       Forms\Components\TextInput::make('codachat')->label('Achat ID')->disabled(),
                       Forms\Components\TextInput::make('modepaie')->label('Mode de paiement')->disabled(),
                       Forms\Components\TextInput::make('montantpaie')->label('Montant payé')->numeric()->disabled(),
                       Forms\Components\DatePicker::make('datepaie')
                       ->label('Date de paiement')
                       ->native(false)
                       ->suffixIcon('heroicon-o-calendar')
                       ->displayFormat('d/m/Y')
                       ->disabled(),
                   ])
                   ->columns(2),
               
               Forms\Components\Section::make('Informations du client')
                   ->schema([
                       Forms\Components\TextInput::make('codeclient')->label('Code Client')->disabled(),
                       Forms\Components\TextInput::make('nomclient')->label('Nom Client')->disabled(),
                       Forms\Components\TextInput::make('prenomclient')->label('Prénom Client')->disabled(),
                   ])
                   ->columns(3),
               
               Forms\Components\Section::make('Banque et Propriétaire')
                   ->schema([
                       Forms\Components\Select::make('codebanque')
                       ->searchable()
                       ->preload()
                       ->relationship('banque','codebanque')
                       ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque} {$record->adressedebanque})")
                       ->label('Banque')
                       ->disabled(),
                       Forms\Components\Select::make('code_proprietaire')
                       ->searchable()
                       ->preload()
                       ->relationship('proprietaire','code_proprietaire')
                       ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                       ->label('Proprietaire')
                       ->disabled(),
                   ])
                   ->columns(2)
           ]);
   }

   public static function table(Table $table): Table
   {
       return $table
           ->columns([
               TextColumn::make('codepaie')
                   ->label('Code Paiement')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),
               
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
               
               TextColumn::make('modepaie')
                   ->label('Mode de paiement')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),
               
               TextColumn::make('montantpaie')
                   ->label('Montant payé')
                   ->money('DZD')
                   ->sortable()
                   ->toggleable(),
               
               TextColumn::make('datepaie')
                   ->label('Date de paiement')
                   ->sortable()
                   ->date('d-m-Y')
                   ->searchable()
                   ->toggleable(),
               
                TextColumn::make('codebanque')
                    ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})")
                    ->label('Banque')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
               
               TextColumn::make('code_proprietaire')
                   ->label('Propriétaire')
                   ->sortable()
                   ->searchable()
                   ->toggleable(),

                TextColumn::make('code_proprietaire')
                    ->formatStateUsing(fn ($record) => "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})")
                    ->label('Propriétaire')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

           ])
           ->filters([
                SelectFilter::make('codeclient')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->label('Code Client')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                    ->preload(),

                SelectFilter::make('nomclient')
                   ->relationship('client', 'nomclient')
                   ->label('Nom Client')
                   ->searchable()
                   ->preload(),

                SelectFilter::make('prenomclient')
                   ->relationship('client', 'prenomclient')
                   ->label('Prénom Client')
                   ->searchable()
                   ->preload(),
               
                SelectFilter::make('modepaie')
                    ->label('Mode de Paie')
                    ->options([
                        'Crédit Bancaire' => 'Crédit Bancaire',
                        'Espece' => 'Espece',
                        'Versement' => 'Versement',
                        'Remboursement' => 'Remboursement',
                    ])
                    ->searchable()
                    ->preload(),

                Filter::make('datepaie1')
                   ->form([
                       Forms\Components\DatePicker::make('value')
                           ->label('Date de Paie')
                           ->native(false)
                           ->suffixIcon('heroicon-o-calendar')
                           ->displayFormat('d/m/Y'),
                   ])
                   ->query(fn (Builder $query, array $data) => 
                       isset($data['value']) && $data['value'] !== ''
                           ? $query->whereDate('datepaie', '=', $data['value'])
                           : $query
                   )
                   ->indicateUsing(fn (array $data) => 
                       isset($data['value']) && $data['value'] !== ''
                           ? 'Date de Paie: ' . \Carbon\Carbon::parse($data['value'])->format('d/m/Y')
                           : null
                   ),                    
               Filter::make('datepaie2')
                   ->form([
                       DatePicker::make('created_from')
                           ->label('Date de Paie A partir de')
                           ->native(false)
                           ->suffixIcon('heroicon-o-calendar')
                           ->displayFormat('d/m/Y'),
                       DatePicker::make('created_until')
                           ->label('Date de Paie Jusqu à')
                           ->native(false)
                           ->suffixIcon('heroicon-o-calendar')
                           ->displayFormat('d/m/Y'),
                   ])
                   ->query(function (Builder $query, array $data): Builder {
                       return $query
                           ->when(
                               $data['created_from'] ?? null,
                               fn (Builder $query, $date): Builder => $query->whereDate('datepaie', '>=', $date),
                           )
                           ->when(
                               $data['created_until'] ?? null,
                               fn (Builder $query, $date): Builder => $query->whereDate('datepaie', '<=', $date),
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

                SelectFilter::make('codebanque')
                    ->label('Banque')
                    ->relationship('banque', 'codebanque')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque} {$record->adressedebanque})")
                    ->preload(),
               
                SelectFilter::make('code_proprietaire')
                    ->label('Propriétaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code_proprietaire} ({$record->nom_proprietaire} {$record->prenom_proprietaire})")
                    ->preload(), 
           ])
           ->actions([
               Tables\Actions\ViewAction::make(),
           ])
           ->bulkActions([]);
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
            'index' => Pages\ListPaiementsDesProprietaires::route('/'),
            'create' => Pages\CreatePaiementsDesProprietaires::route('/create'),
            'edit' => Pages\EditPaiementsDesProprietaires::route('/{record}/edit'),
        ];
    }
}
