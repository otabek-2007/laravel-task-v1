<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OAuthClientResource\Pages;
use App\Models\OAuthClient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OAuthClientResource extends Resource
{
    protected static ?string $model = OAuthClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'OAuth Clients';

    protected static ?string $pluralLabel = 'OAuth Clients';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('Application Name'),

            Forms\Components\TextInput::make('client_id')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Generated automatically'),

            Forms\Components\TextInput::make('client_secret')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Generated automatically'),

            Forms\Components\TextInput::make('redirect_uri')
                ->required()
                ->label('Redirect URI'),

            Forms\Components\Textarea::make('scopes')
                ->rows(2)
                ->helperText('Comma separated scopes, e.g. profile,email'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('client_id')->copyable(),
            Tables\Columns\TextColumn::make('redirect_uri'),
            Tables\Columns\TextColumn::make('scopes'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOAuthClients::route('/'),
            'create' => Pages\CreateOAuthClient::route('/create'),
            'edit' => Pages\EditOAuthClient::route('/{record}/edit'),
        ];
    }
}
