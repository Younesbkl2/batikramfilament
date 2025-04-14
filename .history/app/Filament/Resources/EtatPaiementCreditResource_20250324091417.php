<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\EtatPaiementCredit;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EtatPaiementCreditResource\Pages;
use App\Filament\Resources\EtatPaiementCreditResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;

class EtatPaiementCreditResource extends Resource
{
    protected static ?string $model = EtatPaiementCredit::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Rapports financiers';
    protected static ?string $recordTitleAttribute = 'codeclient';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'codeclient',
            'nomclient', 
            'prenomclient',
            'numappartement',
            'projet.Libelleprj',
            'proprietaire.nom_proprietaire'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Client' => $record->codeclient,
            'Project' => $record->projet->Libelleprj,
            'Appartment' => $record->numappartement
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codeclient')->disabled(),
                Forms\Components\TextInput::make('nomclient')->disabled(),
                Forms\Components\TextInput::make('prenomclient')->disabled(),
                Forms\Components\TextInput::make('Numdetel')->disabled(),
                Forms\Components\TextInput::make('NUM_TEL')->disabled(),
                Forms\Components\TextInput::make('numappartement')->disabled(),
                Forms\Components\TextInput::make('codeprj')->disabled(),
                Forms\Components\TextInput::make('code_proprietaire')->disabled(),
                Forms\Components\Textarea::make('Observations')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codeclient')
                    ->label('Client Code')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('nomclient')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('prenomclient')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('NUM_TEL')
                    ->label('Phone Number')
                    ->searchable(),
                
                TextColumn::make('numappartement')
                    ->label('Appartment')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('projet.Libelleprj')
                    ->label('Project')
                    ->sortable(),
                
                TextColumn::make('proprietaire.full_name')
                    ->label('Owner')
                    ->formatStateUsing(fn ($record) => 
                        $record->proprietaire->nom_proprietaire.' '.$record->proprietaire->prenom_proprietaire),
                
                TextColumn::make('Observations')
                    ->label('Notes')
                    ->wrap()
            ])
            ->filters([
                SelectFilter::make('codeclient')
                    ->relationship('client', 'codeclient')
                    ->searchable(),
                
                SelectFilter::make('codeprj')
                    ->relationship('projet', 'codeprj')
                    ->label('Project'),
                
                SelectFilter::make('code_proprietaire')
                    ->relationship('proprietaire', 'code_proprietaire')
                    ->label('Owner')
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
            'index' => Pages\ListEtatPaiementCredits::route('/'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['projet', 'proprietaire']);
    }

}
