<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;
use App\Models\DeviceModelApplicationLink;
use App\Models\DeviceModel;
//use App\Models\DownloadTask;

class ApplicationView extends Model 
{
    //use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "tms_v_application";
	//const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
    public $timestamps = false;


    public function applicationDeviceModel()
    {
       
        return $this->hasMany('App\Models\DeviceModelApplicationLink','application_id','id');
        // return $this->belongsTo('App\VendorCustomer', 'vendor_customer_id');
    }
   


}
