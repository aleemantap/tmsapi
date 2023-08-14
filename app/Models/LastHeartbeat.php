<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LastHeartbeat extends Model 
{
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_last_heartbeat";
	public $timestamps = false;
    
    
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    public function terminal()
    {
        return $this->hasMany('App\Models\Terminal','terminal_id','id');
    }

     
   

}
