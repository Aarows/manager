<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $usEmployee = Country::where('country_code', 'USA')->withCount('employees')->first() ;
        $ukEmployee = Country::where('country_code', 'Uk')->withCount('employees')->first() ;

        return [
            Card::make('All employees', Employee::all()->count()),
            Card::make($ukEmployee->name. ' employees',  $ukEmployee->employees_count),
            Card::make($usEmployee->name. ' employees',  $usEmployee->employees_count),

        ];
    }
}
