<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'apartment_id',
        'promotion_id'
    ];

    public function apartment(){
        return $this -> belongsTo(Apartment::class);
    }

    public function promotion(){
        return $this -> belongsTo(Promotion::class);
    }
}
