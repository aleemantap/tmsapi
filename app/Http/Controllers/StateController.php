<?php

namespace App\Http\Controllers;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class StateController extends Controller
{
    public function list(Request $request){

        try {

                $pageSize = $request->pageSize;
                $pageNum = $request->pageNum;
                $country_id = $request->country_id;
                $name = $request->name;
                $query = State::query()->whereNull('deleted_by')
                    ->with(['country' => function ($query) {
                        $query->select('id', 'code','name');
                    }]);
                if($request->country_id != '')
                {
                    $query->where('country_id', $request->country_id);
                }
                if($request->name != '')
                {
                    $query->where('name', $request->name);
                }

                $count = $query->get()->count();
            
                $results = $query->offset(($pageNum-1) * $pageSize) 
                ->limit($pageSize)->orderBy('name', 'ASC')->get();
                
            
                return response()->json(['responseCode' => '0000', 
                                        'responseDesc' => 'OK',
                                        'pageSize'  =>  $pageSize,
                                        'totalPage' => ceil($count/$pageSize),
                                        'total' => $count,
                                        'rows' => $results
                                    ]);
        } catch (\Exception $e) {
            return response()->json(['status' => '3333', 'message' => $e->getMessage()]);
        }
    }


    public function create(Request $request){

        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'country_id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $st = new State();
            $st->version = 1; 
            $st->name = $request->name;
            $st->country_id = $request->country_id;

            if ($st->save()) {
                return response()->json(['responseCode' => '0000', //sukses insert
                                          'responseDesc' => 'State created successfully',
                                          
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', //gagal exception 
                                     'responseDesc' => 'State created Failure'
           ]);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'version' => 'required|numeric|max:32',
            'name' => 'required|max:50',
            'country_id' => 'required',
            'id' => 'required' 
        ]);
 
        if ($validator->fails()) {
            return response()->json(['responseCode' => '5555', //gagal validasi
                                     'responseDesc' => $validator->errors()]
                                    );
        }

        try {

            $st = State::where([
                ['id',$request->id],
                ['version',$request->version],
                ['country_id', $request->country_id]
            ])->first();

            $st->version = $request->version + 1;
            $st->name = $request->name;
            
            if ($st->save()) {
                return response()->json(['responseCode' => '0000', //sukses update
                                          'responseDesc' => 'State updated successfully',
                                        ]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => "State Update Failure"]);
        }
    }
    
    public function show(Request $request){
        try {
            $state = State::where('id', $request->id)->with(['country' => function ($query) {
                $query->select('id', 'code','name');
            }])->get();
            if($state->count()>0)
            {
                return response()->json([
                    'responseCode' => '0000', 
                    'responseDesc' => 'OK',
                    //'data' => $state
                    'data' => collect($state)->map(function($item) {
                        return collect($item)->except('country_id');
                        //menghilangakn kolom country_id
                    })->toArray()
                ]);
            }
            else
            {
                return response()->json([
                    'responseCode' => '0400', 
                    'responseDesc' => 'Data Not Found',
                    'data' =>  $state                    
                ]);
            }
            
        }
        catch(\Exception $e)
        {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }

    public function delete(Request $request){
        try {
            $state = State::where([
                ['id',$request->id],
                ['version', $request->version]
            ])->first();
            $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
            $state->delete_ts = $current_date_time; 
            $state->deleted_by = "admin";//Auth::user()->id
            
            if ($state->save()) {
                return response()->json(['responseCode' => '0000', 'responseDesc' => 'State deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => '3333', 'responseDesc' => $e->getMessage()]);
        }
    }

    
}
