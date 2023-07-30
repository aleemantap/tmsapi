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
        $model->updated_by = Session::get('X-Consumer-Username');//"";//Auth::user()->id;
    }

    public function updating(Model $model)
    {
        $model->updated_by = Session::get('X-Consumer-Username');//app('Illuminate\Http\Request')->header('X-Consumer-Username');//"1";//Auth::user()->id;
       
    }

}
