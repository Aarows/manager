<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Forms\Components\Card ;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use http\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
               /* ->schema([

                    Forms\Components\Select::make('country_id')
                        ->relationship('country' , 'name')
                        ->required()
                        ->placeholder('select your country'),
                    Forms\Components\Select::make('city_id')
                        ->relationship('city' , 'name')
                        ->required()
                        ->placeholder('select your town'),
                    Forms\Components\Select::make('department_id')
                        ->relationship('department' , 'name')
                        ->required()
                        ->placeholder('choose a department'),
                    Forms\Components\TextInput::make('firstname')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('lastname')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('zip_code')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('birth_date')
                        ->required(),
                    Forms\Components\DatePicker::make('date_hired')
                        ->required(),
                ])*/
                ->schema([
                    Forms\Components\Select::make('country_id')
                        ->label('Country')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                   Forms\Components\Select::make('city_id')
                        ->label('City')
                        ->options(function (callable $get){
                            $country = Country::find($get('country_id'));
                            if(!$country)
                            {
                                return City::all()->pluck('name' , 'id') ;
                            }
                            return $country->cities->pluck('name', 'id');
                        })
                        ->reactive(),
                   Forms\Components\Select::make('department_id')
                       ->relationship('department' , 'name')
                       ->required()
                       ->placeholder('choose a department'),
                   Forms\Components\TextInput::make('firstname')
                       ->required()
                       ->maxLength(255),
                   Forms\Components\TextInput::make('lastname')
                       ->required()
                       ->maxLength(255),
                   Forms\Components\TextInput::make('address')
                       ->required()
                       ->maxLength(255),
                   Forms\Components\TextInput::make('zip_code')
                       ->required()
                       ->maxLength(255),
                   Forms\Components\DatePicker::make('birth_date')
                       ->required(),
                   Forms\Components\DatePicker::make('date_hired')
                       ->required(),
               ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')->label('First Name')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('lastname')->label('Last Name')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('city.name')->label('City')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('department.name')->label('department'),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')->relationship('department' , 'name')
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

    public static function getWidgets(): array
    {
        return [

            EmployeeStatsOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
