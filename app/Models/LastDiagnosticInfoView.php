<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
//use App\Models\Heartbeat;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LastDiagnosticInfoView extends Model 
{
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_v_last_diagnostic_info";
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
	//public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
    protected $installedApps = array();

    public function getInstalledApps() {
        return $this->installedApps;
    }

    public function setInstalledApps($installedApps) {
        $this->installedApps = $installedApps;
    }

    protected $casts = [
        'batteryTemp' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'totalLengthPrinted' => 'float',
    ];

   

    
}

