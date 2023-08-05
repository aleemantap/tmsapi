<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;

class  DeleteTask extends Model 
{
    //use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_delete_task";
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
    public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
    public function applications()
    {
        //return $this->belongshasManyToMany(DeleteTaskApp::class);
        return $this->hasMany('App\Models\DeleteTaskApp','task_id','id');
        //return $this->belongsToMany(Application::class, 'tms_download_task_application_link');
    }
    

    public function deletetaskapp()
    {
       
        return $this->hasMany('App\Models\DeleteTaskApp','task_id','id');
    }

    public function deletetaskTerminalLink()
    {
        return $this->hasMany('App\Models\DeleteTaskTerminalLink','delete_task_id','id');
    }

    public function deletetaskTerminalGroupLink()
    {
        return $this->hasMany('App\Models\DeleteTaskTerminalGroupLink','delete_task_id','id');
    }

    


}
