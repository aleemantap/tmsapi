<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Heartbeat;
use App\Models\DiagnosticInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Terminal extends Model 
{
    //use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_terminal";
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
	public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
	

    public function TerminalGroupLink()
    {
        return $this->belongsTo('App\Models\TerminalGroupLink', 'terminal_id', 'terminal_group_id');
      

    }

    public function model()
    {
        return $this->belongsTo('App\Models\DeviceModel', 'model_id', 'id');
       
    }
    
    /**
     * Summary of MerchantModel
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id', 'id');
       
    }
    
    public function profile()
    {
        return $this->belongsTo('App\Models\DeviceProfile', 'profile_id', 'id');
      
    }

    /**
     * Summary of DownloadTask
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function DownloadTask()
    {
        return $this->belongsTo('App\Models\DownloadTask','download_task_id','id');
    }

    public function lastHeartBeat()
    {
        
        return $this->belongsToMany(Heartbeat::class, 'tms_last_heartbeat');
    }

    public function lastDiagnosticInfo()
    {
       
        return $this->belongsToMany(DiagnosticInfo::class, 'tms_last_diagnostic_info');
    }

    
}
