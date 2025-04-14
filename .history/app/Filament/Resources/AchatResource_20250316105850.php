<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchatResource\Pages;
use App\Filament\Resources\AchatResource\RelationManagers;
use App\Models\Achat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;

class AchatResource extends Resource
{
    protected static ?string $model = Achat::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Tables';
    
    public static function getNavigationBadge(): ?string
    {
        $totalCount = static::getModel()::count(); // Get total count
        $trashedCount = static::getModel()::onlyTrashed()->count(); // Get soft-deleted count
    
        // If there are soft-deleted entries, append the count in parentheses
        return $trashedCount > 0 ? "{$totalCount} (Corbeille: {$trashedCount})" : (string) $totalCount;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    
    
    
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('codeclient')
                ->relationship('client','codeclient')
                ->searchable()
                ->preload()
                ->required(),
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
                Tables\Columns\TextColumn::make('codachat')->sortable(),
                Tables\Columns\TextColumn::make('codeclient')->sortable(),
                Tables\Columns\TextColumn::make('codeprod')
                ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})")
                ->sortable(),
                Tables\Columns\TextColumn::make('codebanque')
                ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque})")
                ->sortable(),
                Tables\Columns\TextColumn::make('numappartement')->sortable(),
                Tables\Columns\TextColumn::make('numparking')->sortable(),
                Tables\Columns\TextColumn::make('Numlocal')->sortable(),
                Tables\Columns\TextColumn::make('Observations'),
                Tables\Columns\TextColumn::make('ID_ATTESTATION')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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

    public static function query(Builder $query): Builder
    {
        return $query->withoutGlobalScopes(); // Shows all records (both soft-deleted & active)
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }     
}
