<?php

namespace App\Models;

use App\Models\Tlesetting;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acquirer extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_acquirer";
	public $timestamps = false;


	public function tle_setting()
    {
        //return $this->hasMany(Tlesetting::class);
        return $this->hasMany('App\Models\Tlesetting');//, 'tle_setting_id', 'id'
    }


}
