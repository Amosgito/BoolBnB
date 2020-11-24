<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [

        'apartment_id'
    ];

    public function apartment() {

        return $this -> belongsTo(Apartment::class);
    }
}
