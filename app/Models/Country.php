<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;


class Country extends Model 
{
    //use Blameable;
    use HasFactory;
    use Uuid;
	//protected $primaryKey = 'uuid';
    protected $table = "tms_country";
    //const CREATED_AT = 'create_ts';
    //const UPDATED_AT = 'update_ts';
	//const DELETED_AT = 'delete_ts';
    public $timestamps = false;

   
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
   

    protected $fillable = [
        'version', 'create_ts', 'created_by', 'update_ts', 'updated_by', 'delete_ts', 'deleted_by', 'code', 'name'
    ];
	
	public function state()
    {
       
        return $this->hasMany('App\Models\State');
    }

}
