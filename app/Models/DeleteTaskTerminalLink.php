<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class DeleteTaskTerminalLink extends Model 
{
    use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "tms_delete_task_terminal_link";
	//const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
    
	//public function deletetaskTerminalLink()
    //{
    //    return $this->hasMany('App\Models\DeleteTaskTerminalLink','delete_task_id','id');
    //}


}
