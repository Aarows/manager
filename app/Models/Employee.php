<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [

        'firstname',
        'lastname',
        'address',
        'city_id',
        'country_id',
        'department_id',
        'date_hired',
        'birth_date',
        'zip_code'

    ];

    public function department()
    {
        return $this->belongsTo(Department::class) ;
    }

    public function city()
    {
        return $this->belongsTo(City::class) ;
    }

    public function country()
    {
        return $this->belongsTo(Country::class) ;
    }


}
