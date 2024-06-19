<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Candidate extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'party','dob','age','number','candidate_photo','address_key'];
    
    
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_key');
    }
   
}
