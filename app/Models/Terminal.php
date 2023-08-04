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
	public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
	// public function state()
    // {
       
    //     return $this->hasMany('App\Models\State');
    // }

    public function TerminalGroupLink()
    {
        return $this->belongsTo('App\Models\TerminalGroupLink', 'terminal_id', 'terminal_group_id');
       //return $this->belongsTo('App\Models\State');

    }

    public function model()
    {
        return $this->belongsTo('App\Models\DeviceModel', 'model_id', 'id');
       //return $this->belongsTo('App\Models\State');
    }
    
    /**
     * Summary of MerchantModel
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Merchant()
    {
        return $this->belongsTo('App\Models\Merchant', 'merchant_id', 'id');
       //return $this->belongsTo('App\Models\State');
    }
    
    public function profile()
    {
        return $this->belongsTo('App\Models\DeviceProfile', 'profile_id', 'id');
       //return $this->belongsTo('App\Models\State');
    }

    /**
     * Summary of DownloadTask
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function DownloadTask()
    {
        return $this->belongsTo('App\Models\DownloadTask','download_task_id','id');
    }
}
