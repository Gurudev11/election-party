<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'address';
    protected $fillable = ['address1', 'address2','city','state'];
    
    public function parties()
    {
        return $this->hasMany(Party::class, 'address_key');
    }
    
    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'address_key');
    }
   
}
    