<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ActFinalResource\Pages;
use App\Filament\App\Resources\ActFinalResource\RelationManagers;
use App\Models\ActFinal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActFinalResource extends Resource
{
    protected static ?string $model = ActFinal::class;

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
            'index' => Pages\ListActFinals::route('/'),
            'create' => Pages\CreateActFinal::route('/create'),
            'edit' => Pages\EditActFinal::route('/{record}/edit'),
        ];
    }
}
