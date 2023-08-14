<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LastDiagnosticInfo extends Model 
{
    use HasFactory;
    use Uuid;
	
    protected $table = "tms_last_diagnostic_info";
	public $timestamps = false;
    
    
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    
   

}
