<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;

/**
 * Summary of DownloadTaskApplicationLink
 */
class DownloadTaskApplicationLink extends Model 
{
    //use Blameable;
	use HasFactory;
    use Uuid;
       /**
        * Summary of table
        * @var string
        */
    protected $table = "tms_download_task_application_link";
	//const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
    
	//public function state()
    //{
    //    return $this->belongsTo('App\Models\State', 'states_id', 'id');
    //}

   /*  public function application()
    {
        return $this->belongsTo('App\Models\Application');
    }

    /**
     * Summary of DownloadTask
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
  /*   public function DownloadTask()
    {
        return $this->belongsTo('App\Models\DownloadTask');
    } */


}
