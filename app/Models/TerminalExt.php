<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TerminalExt extends Model 
{
	use HasFactory;
    use Uuid;
    protected $table = "tmsext_terminal_ext";
	public $timestamps = false;


}
