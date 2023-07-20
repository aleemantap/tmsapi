<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use App\Blameable;

class Exportprocesses extends Model
{
    //use Blameable;
	use HasFactory;
    use Uuid;
    protected $table = "exportProcesses";
}
