<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class DiagnosticInfo extends Model 
{
    use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "tms_diagnostic_info";
	const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    public $timestamps = false;
	//public function state()
    //{
    //    return $this->belongsTo('App\Models\State', 'states_id', 'id');
    //}


}
