<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Users management' ;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Card::make()
               ->schema([
                   Forms\Components\TextInput::make('name')
                       ->required()
                       ->regex('/^[a-zA-Z\s]+$/')
                       ->maxLength(255)
                       ->placeholder('enter your name'),
                   Forms\Components\TextInput::make('email')
                       ->email()
                       ->required()
                       ->placeholder('enter your email'),
                   Forms\Components\TextInput::make('password')
                       ->password()
                       ->required(fn (Page $page): bool => $page instanceof CreateRecord)
                       ->minLength(8)
                       ->placeholder('enter the password')
                       ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                       ->same('passwordConfirmation')
                       ->dehydrated(fn ($state) => filled($state)),
                   Forms\Components\TextInput::make('passwordConfirmation')
                        ->password()
                        ->required(fn (Page $filament): bool => $filament instanceof CreateRecord)
                        ->minLength(8)
                        ->placeholder('confirm your password')
                        ->dehydrated(false)
                        ->label('Password Confirmation'),

               ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                ->searchable()->sortable()->label('Users name'),
                Tables\Columns\TextColumn::make('created_at')->label('Creation date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
