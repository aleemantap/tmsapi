<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class TerminalGroup extends Model 
{
    use Blameable;
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_terminal_group";
    const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
	public $timestamps = false;
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   


}
