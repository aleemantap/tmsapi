<?php

namespace App\Models;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Issuer;

class Template extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_template";
	public $timestamps = false;

	


}
