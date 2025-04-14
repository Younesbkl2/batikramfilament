<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    public static function getNavigationGroup(): ?string
    {
        return 'Gestion des utilisateurs';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('username')
                    ->required(),
                    
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),

                // Using Select Component
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('username')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->sortable()
                    ->dateTime('d-m-Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true),   

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('name')
                ->label('Nom')
                ->searchable()
                ->preload()
                ->options(fn () => User::pluck('name', 'name')->toArray()),
            
                Tables\Filters\SelectFilter::make('username')
                ->label('Nom Utilisateur')
                ->searchable()
                ->preload()
                ->options(fn () => User::pluck('username', 'username')->toArray()),

                Tables\Filters\SelectFilter::make('email')
                ->label('Email Utilisateur')
                ->searchable()
                ->preload()
                ->options(fn () => User::pluck('email', 'email')->toArray()),   
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
