<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'registration_date','number', 'party_logo', 'address_key'];

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_key');
    }
}

  

