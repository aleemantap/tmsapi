<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;

/**
 * Summary of DownloadTaskApplicationLink
 */
class DeviceModelApplicationLink extends Model 
{
    
	use HasFactory;
    use Uuid;
       /**
        * Summary of table
        * @var string
        */
    protected $table = "tms_device_model_application_link";
	


}
