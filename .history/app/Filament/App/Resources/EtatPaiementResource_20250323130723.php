<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EtatPaiementResource\Pages;
use App\Filament\App\Resources\EtatPaiementResource\RelationManagers;
use App\Models\EtatPaiement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EtatPaiementResource extends Resource
{
    protected static ?string $model = EtatPaiement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEtatPaiements::route('/'),
            'create' => Pages\CreateEtatPaiement::route('/create'),
            'edit' => Pages\EditEtatPaiement::route('/{record}/edit'),
        ];
    }
}
