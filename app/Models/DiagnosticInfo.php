<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Terminal;


class DiagnosticInfo extends Model 
{
   
	use HasFactory;
    use Uuid;
    protected $table = "tms_diagnostic_info";
	
    public $timestamps = false;
   
	
    public function terminals()
    {
        return $this->belongsToMany(Terminal::class, 'tms_last_diagnostic_info');
    }

}
