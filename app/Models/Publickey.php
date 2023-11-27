<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publickey extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_public_key_setting";
	public $timestamps = false;


}
