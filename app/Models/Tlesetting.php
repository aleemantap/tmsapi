<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tlesetting extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_tle_setting";
	public $timestamps = false;

	// protected $fillable = [
 //        'version', 'create_ts', 'created_by', 'update_ts', 'updated_by', 'delete_ts', 'deleted_by', 'tleId'
 //    ];

	public function acquirer()
    {
        return $this->belongsTo('App\Models\Acquirer', 'tle_setting_id', 'id');
    }

	// public function tleSetting()
 //    {
 //        return $this->belongsTo('App\Models\Tlesetting', 'tle_setting_id', 'id');
 //    }


}
