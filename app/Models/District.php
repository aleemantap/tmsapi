<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Blameable;

class District extends Model 
{
    use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "tms_district";
	const CREATED_AT = 'create_ts';
    const UPDATED_AT = 'update_ts';
    public $timestamps = false;

    protected $fillable = [
        'version', 'create_ts', 'created_by', 'update_ts', 'updated_by', 'delete_ts', 'deleted_by', 'name','city_id'
    ];
	
	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }


}
