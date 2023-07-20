<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class Terminal extends Model 
{
    use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_terminal";
    const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
	// public function state()
    // {
       
    //     return $this->hasMany('App\Models\State');
    // }

}
