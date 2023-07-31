<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;

class  DeleteTaskApp extends Model 
{
    //use Blameable;
    //use HasFactory;
    use Uuid;
	
    protected $table = "tms_delete_task_app";
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';

    protected $fillable = [
        'id', 'app_name' //, 'package_name', 'app_version','task_id'
    ];
   
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */

    public function deletetask()
    {
        //return $this->belongsTo('App\Moel\DeleteTaskApp', '');
        return $this->belongsTo('App\Models\DeleteTask','id','task_id');
    }
   


}
