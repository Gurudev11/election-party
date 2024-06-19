<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'state_id'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
