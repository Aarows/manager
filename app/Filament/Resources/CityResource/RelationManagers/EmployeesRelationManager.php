<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use App\Models\City;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
