<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExampleResource\Pages;
use App\Filament\Resources\ExampleResource\RelationManagers;
use App\Models\Example;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExampleResource extends Resource
{
    protected static ?string $model = Example::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('feature_one')
                    ->label('Feature One')
                    ->reactive(),
                Forms\Components\TextInput::make('feature_one_attributed_at')
                    ->label('First Attributed At')
                    ->disabled(),
                Forms\Components\TextInput::make('feature_one_modified_at')
                    ->label('Last Modified At')
                    ->disabled(),

                Forms\Components\Toggle::make('feature_two')
                    ->label('Feature Two')
                    ->reactive(),
                Forms\Components\TextInput::make('feature_two_attributed_at')
                    ->label('First Attributed At')
                    ->disabled(),
                Forms\Components\TextInput::make('feature_two_modified_at')
                    ->label('Last Modified At')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('feature_one')
                    ->label('Feature One')
                    ->boolean(),
                Tables\Columns\TextColumn::make('feature_one_attributed_at')
                    ->label('First Attributed At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('feature_one_modified_at')
                    ->label('Last Modified At')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('feature_two')
                    ->label('Feature Two')
                    ->boolean(),
                Tables\Columns\TextColumn::make('feature_two_attributed_at')
                    ->label('First Attributed At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('feature_two_modified_at')
                    ->label('Last Modified At')
                    ->dateTime(),
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
            'index' => Pages\ListExamples::route('/'),
            'create' => Pages\CreateExample::route('/create'),
            'edit' => Pages\EditExample::route('/{record}/edit'),
        ];
    }
}
