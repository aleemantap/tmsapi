<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Acquirer;

class Issuer extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_issuer";
	public $timestamps = false;

	public function acquirer()
    {
        
         return $this->belongsTo('App\Models\Acquirer', 'acquirer_id', 'id');
        // return $this->belongsTo(Acquirer::class);


    }

	//public function card()
    //{
    //     return $this->belongsTo('App\Models\Acquirer', 'tle_setting_id', 'id');
    //}



}
