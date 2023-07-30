<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;
use App\Models\Application;

class  DownloadTask extends Model 
{
    use Blameable;
    use HasFactory;
    use Uuid;
    protected $table = "tms_download_task";
    const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
    public function terminal()
    {
       return $this->hasMany(Terminal::class);
    }
    
    public function applications()
    {
        //return $this->belongsTo('App\Models\DownloadTaskApplicationLink');
        //return $this->hasMany(DownloadTaskApplicationLink::class);
        return $this->belongsToMany(Application::class, 'tms_download_task_application_link');
    }
    
    public function application()
    {
        //return $this->belongsTo('App\Models\DownloadTaskApplicationLink');
        return $this->hasMany(Application::class,'application_id','id');
        //return $this->belongsToMany(Application::class, 'tms_download_task_application_link');
    }
    

    

}
