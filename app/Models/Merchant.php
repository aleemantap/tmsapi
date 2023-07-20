<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class Merchant extends Model 
{
    use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_merchant";
    const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    protected $hidden = ['type_id','tenant_id','district_id']; 
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    
   	public function merchanttype()
    {
       
         return $this->belongsTo('App\Models\MerchantType','type_id','id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

}
