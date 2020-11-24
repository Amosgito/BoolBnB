<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'hours',
        'price',
    ];

    public function sponsorships(){
        return $this -> hasMany(Sponsorship::class);
    }
}
