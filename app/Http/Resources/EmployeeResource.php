<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname ,
            'address' => $this->address,
            'countryId' => $this->country_id,
            'cityId' => $this->city_id,
            'departmentId' => $this->department_id,
            'zip_code' => $this->zip_code,
            'date_hired' => $this->date_hired,
        ];
    }
}
