<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;
use App\Models\DownloadTaskApplicationLink;
use App\Models\DownloadTask;

class Application extends Model 
{
    use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "tms_application";
	const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    
    protected $hidden = array('pivot');
    
    public function downloadTask()
    {
       //return $this->hasMany(DownloadTask::class,'tms_download_task_application_link');
       return $this->belongsToMany(DownloadTask::class, 'tms_download_task_application_link');
    }
    
    /**
     * Summary of DownloadTaskApplicationLink
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DownloadTaskApplicationLink()
    {
       return $this->hasMany(DownloadTaskApplicationLink::class);
    }

}
