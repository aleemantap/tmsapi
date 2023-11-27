<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aid extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_aid";
	public $timestamps = false;


}
