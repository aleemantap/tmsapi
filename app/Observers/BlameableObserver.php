<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Auth;

class BlameableObserver
{
    public function creating(Model $model)
    {
        $model->created_by = Session::get('X-Consumer-Username');
        //$model->updated_by = Session::get('X-Consumer-Username');//"";//Auth::user()->id;
        //$model->timestamps = false;
        //$model->created_at = \Carbon\Carbon::now()->toDateTimeString();
    }

    public function updating(Model $model)
    {
       
            $model->updated_by = Session::get('X-Consumer-Username');            
            //$current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            //$model->updated_at = $current_date_time; 
        
    }

}
