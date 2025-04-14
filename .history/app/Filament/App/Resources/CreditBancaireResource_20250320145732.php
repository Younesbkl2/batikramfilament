<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CreditBancaireResource\Pages;
use App\Filament\App\Resources\CreditBancaireResource\RelationManagers;
use App\Models\CreditBancaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditBancaireResource extends Resource
{
    protected static ?string $model = CreditBancaire::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Suivi Client';

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
            'index' => Pages\ListCreditBancaires::route('/'),
            'create' => Pages\CreateCreditBancaire::route('/create'),
            'edit' => Pages\EditCreditBancaire::route('/{record}/edit'),
        ];
    }
}
