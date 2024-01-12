<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResponseCode extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_response_code";
	public $timestamps = false;

}
