<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory,SoftDeletes;

    /*The table associated with the model.
    will be use when
    1. table created manually from database
    2. model/table created separately from migration
    */
    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'city_id',
        'state_id',
        'address',
        'postal_code',
    ];

    public function states(){
       return $this->belongsTo(State::class,'state_id');
    }

    public function cities(){
       return $this->belongsTo(City::class,'city_id','id');
    }
}
