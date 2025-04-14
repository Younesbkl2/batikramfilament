<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AchatResource\Pages;
use App\Filament\App\Resources\AchatResource\RelationManagers;
use App\Models\Achat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;


class AchatResource extends Resource
{
    protected static ?string $model = Achat::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Tables';

    // Define the record title attribute for global search
    protected static ?string $recordTitleAttribute = 'codachat';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'codachat',
            'codeclient',
            'client.nomclient',  // Search by nomclient from the clients table
            'client.prenomclient' // Search by prenomclient from the clients table
        ];
    }
    
    

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('codeclient')
                ->relationship('client','codeclient')
                ->searchable()
                ->preload()
                ->required()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})"),
                Forms\Components\Select::make('codeprod')
                ->relationship('produit','codeprod')
                ->searchable()
                ->preload()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})"),
                Forms\Components\Select::make('codebanque')
                ->relationship('banque','codebanque')
                ->searchable()
                ->preload()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque})"),
                Forms\Components\Select::make('numappartement')
                ->searchable()
                ->preload()
                ->relationship('appartement','numappartement'),
                Forms\Components\Select::make('numparking')
                ->searchable()
                ->preload()
                ->relationship('parking','numparking'),
                Forms\Components\Select::make('Numlocal')
                ->searchable()
                ->preload()
                ->relationship('local','Numlocal'),
                Forms\Components\Select::make('ID_ATTESTATION')
                ->searchable()
                ->preload()
                ->relationship('attestation','ID_ATTESTATION'),
                Forms\Components\Textarea::make('Observations'),

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
                Tables\Columns\TextColumn::make('codachat')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codeclient')->sortable()->searchable()->toggleable()
                ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})"),
                Tables\Columns\TextColumn::make('codeprod')
                ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})")
                ->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('codebanque')
                ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque})")
                ->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('numappartement')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('numparking')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Numlocal')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('Observations')->toggleable(),
                Tables\Columns\TextColumn::make('ID_ATTESTATION')->sortable()->searchable()->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
            
                Tables\Filters\SelectFilter::make('codeclient')
                    ->label('Client')
                    ->relationship('client', 'codeclient')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})")
                    ->preload(),
            
                Tables\Filters\SelectFilter::make('codeprod')
                    ->label('Produit')
                    ->relationship('produit', 'codeprod')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codeprod} ({$record->Typeproduit})"),
            
                Tables\Filters\SelectFilter::make('codebanque')
                    ->label('Banque')
                    ->relationship('banque', 'codebanque')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codebanque} ({$record->nomdebanque})"),
            
                Tables\Filters\SelectFilter::make('numappartement')
                    ->label('Appartement')
                    ->relationship('appartement', 'numappartement')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('numparking')
                    ->label('Parking')
                    ->relationship('parking', 'numparking')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('Numlocal')
                    ->label('Local')
                    ->relationship('local', 'Numlocal')
                    ->searchable()
                    ->preload(),
                    Tables\Filters\SelectFilter::make('ID_ATTESTATION')
                    ->label('Attestation')
                    ->relationship('attestation', 'ID_ATTESTATION')
                    ->searchable()
                    ->preload(),  
                                    
            
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAchats::route('/'),
            'create' => Pages\CreateAchat::route('/create'),
            'edit' => Pages\EditAchat::route('/{record}/edit'),
        ];
    }


    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->withoutTrashed()->with(['client']);

    }

}
