<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;
use App\Models\City;

class State extends Model 
{
    //use Blameable;
	//use HasUuids;
    use HasFactory;
    use Uuid;
	//protected $primaryKey = 'uuid';
    protected $table = "tms_states";
	//const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
	public $timestamps = false;
    

    protected $fillable = [
        'version', 'create_ts', 'created_by', 'update_ts', 'updated_by', 'delete_ts', 'deleted_by', 'name'
    ];
	
	public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function city()
    {
        //return $this->hasMany('App\Models\City');
        return $this->hasMany(City::class);
    }
   


}
