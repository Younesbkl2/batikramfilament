<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaiementsDesProprietaires;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaiementsDesProprietairesResource\Pages;
use App\Filament\Resources\PaiementsDesProprietairesResource\RelationManagers;

class PaiementsDesProprietairesResource extends Resource
{
    protected static ?string $model = PaiementsDesProprietaires::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Financial Reports';

    // Disable create button
    public static function canCreate(): bool
   {
      return false;
   }


   public static function form(Form $form): Form
   {
       return $form
           ->schema([
               Forms\Components\Section::make('Payment Details')
                   ->schema([
                       Forms\Components\TextInput::make('codepaie')->disabled(),
                       Forms\Components\TextInput::make('codachat')->disabled(),
                       Forms\Components\TextInput::make('modepaie')->disabled(),
                       Forms\Components\TextInput::make('montantpaie')->numeric()->disabled(),
                       Forms\Components\DateTimePicker::make('datepaie')->disabled(),
                   ])
                   ->columns(2),
               
               Forms\Components\Section::make('Client Information')
                   ->schema([
                       Forms\Components\TextInput::make('codeclient')->disabled(),
                       Forms\Components\TextInput::make('nomclient')->disabled(),
                       Forms\Components\TextInput::make('prenomclient')->disabled(),
                   ])
                   ->columns(3),
               
               Forms\Components\Section::make('Bank & Ownership')
                   ->schema([
                       Forms\Components\TextInput::make('codebanque')->disabled(),
                       Forms\Components\TextInput::make('code_proprietaire')->disabled(),
                   ])
                   ->columns(2)
           ]);
   }

   public static function table(Table $table): Table
   {
       return $table
           ->columns([
               TextColumn::make('codepaie')
                   ->label('Payment ID')
                   ->sortable()
                   ->searchable(),
               
               TextColumn::make('codachat')
                   ->label('Achat ID')
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
               
               TextColumn::make('modepaie')
                   ->label('Payment Method')
                   ->sortable(),
               
               TextColumn::make('montantpaie')
                   ->label('Amount')
                   ->money('USD')
                   ->sortable(),
               
               TextColumn::make('datepaie')
                   ->label('Date')
                   ->dateTime()
                   ->sortable(),
               
               TextColumn::make('codebanque')
                   ->label('Bank Code')
                   ->sortable(),
               
               TextColumn::make('code_proprietaire')
                   ->label('Owner Code')
                   ->sortable()
           ])
           ->filters([
               SelectFilter::make('codeclient')
                   ->relationship('client', 'codeclient')
                   ->searchable(),
               
               SelectFilter::make('modepaie')
                   ->options([
                       'Cash' => 'Cash',
                       'Check' => 'Check',
                       'Transfer' => 'Transfer'
                   ]),
               
               SelectFilter::make('codebanque')
                   ->relationship('banque', 'codebanque'),
               
               SelectFilter::make('code_proprietaire')
                   ->relationship('proprietaire', 'code_proprietaire')
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
        ];
    }
}
