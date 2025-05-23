<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('codeclient')->required(),
                Forms\Components\TextInput::make('nomclient')->required(),
                Forms\Components\TextInput::make('prenomclient')->required(),
                Forms\Components\TextInput::make('adresseclient'),
                Forms\Components\TextInput::make('Numdetel')->tel(),
                Forms\Components\TextInput::make('NUM_TEL')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\DatePicker::make('date_de_naissance')
                ->maxDate(now())
                ->native(false)
                ->suffixIcon('heroicon-o-calendar')
                ->displayFormat('d/m/Y'),                
                Forms\Components\FileUpload::make('photo')->directory('photos'),
                Forms\Components\FileUpload::make('dossier')
                ->directory('pdfs')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(10240)
                ->visibility('public'),
                Forms\Components\Checkbox::make('Vsp_publié')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codeclient')->sortable(),
                Tables\Columns\TextColumn::make('nomclient')->sortable(),
                Tables\Columns\TextColumn::make('prenomclient')->sortable(),
                Tables\Columns\TextColumn::make('adresseclient'),
                Tables\Columns\TextColumn::make('Numdetel'),
                Tables\Columns\TextColumn::make('NUM_TEL'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\ImageColumn::make('photo')
                ->circular()
                ->disk('public') // Ensures it reads from the correct disk
                ->extraAttributes(['class' => 'cursor-pointer'])
                ->url(fn ($state) => $state ? asset('storage/' . $state) : null) // Ensures a valid URL
                ->openUrlInNewTab(),
                Tables\Columns\IconColumn::make('dossier')
                ->icon(fn ($state) => $state ? 'heroicon-o-document-text' : 'heroicon-o-x')
                ->url(fn ($state) => $state ? asset('storage/' . $state) : null, true)
                ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('date_de_naissance')->date('d-m-Y')->sortable(),
                Tables\Columns\IconColumn::make('Vsp_publié')
                ->boolean()
                ->sortable(),                                
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteAction::make()
                        ->before(function ($record) {
                            if (!$record) {
                                throw new \Exception('Record not found!');
                            }
                        }),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function query(EloquentBuilder $query): EloquentBuilder
    {
        return $query->onlyTrashed(); // Shows only soft-deleted records
    }
    

}
