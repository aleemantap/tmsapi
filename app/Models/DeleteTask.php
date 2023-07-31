<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class  DeleteTask extends Model 
{
    use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_delete_task";
    const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   
    public function applications()
    {
        return $this->belongsToMany(DeleteTaskApp::class, 'tms_delete_task_terminal_link');
    }
    


}
