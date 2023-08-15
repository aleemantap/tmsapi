<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LastHeartBeatView extends Model 
{
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_v_last_heart_beat"; 
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
	//public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */

     protected $casts = [
        'batteryTemp' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
   
	

    
}

