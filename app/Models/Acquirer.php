<?php

namespace App\Models;

use App\Models\Tlesetting;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Issuer;

class Acquirer extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_acquirer";
	public $timestamps = false;

	//protected $fillable = ['id', 'name', 'version'];

	public function tle_setting()
    {
        return $this->hasMany('App\Models\Tlesetting');
    }

    public function issuer()
    {
        //return $this->hasMany(Issuer::class);
        return $this->hasMany('App\Models\Issuer');
    }


}
