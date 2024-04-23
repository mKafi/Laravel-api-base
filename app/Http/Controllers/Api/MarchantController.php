<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Marchant\StoreMarchantRequest;
use App\Http\Requests\Api\Marchant\UpdateMarchantRequest;
use App\Models\Api\Marchant;

class MarchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiResponse = [];
        $itemArray = [];
        $qRes = Marchant::orderBy('id','desc')->get();
        if($qRes->count()){   
            $revRes = $qRes->all();
            $dbFields = [
                'id' => 'id',                
                'owner' => 'marchant', 
                'organization' => 'company',
                'description' => 'details',
                'contact' => 'phone',
                'comment' => 'comment',            
                'status' => 'status'
            ];

            foreach($revRes AS $item){
                $tempArray = [];
                foreach($dbFields AS $k=>$v){
                    $fieldVal = $item->$k;
                    $tempArray[$v] = !empty($fieldVal) ? $fieldVal : '';
                }
                $itemArray[] = $tempArray;
            }
            $apiResponse = [
                'apiStatus' => 2002,
                'marchants' => $itemArray,
                'message' => 'Records fetched successfully'
            ];
        }
        else{
            $apiResponse = [
                'apiStatus' => 9001,
                'message' => 'API response is empty'
            ];
        }
        return response()->json($apiResponse, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarchantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarchantRequest $request)
    {
        $apiResponse = [];
        $traderCode = $request->header('trader');
        $storeItems = [];
        $dbFields = [
            'owner' => 'marchant', 
            'organization' => 'company',
            'description' => 'details',
            'contact' => 'phone',
            'comment' => 'comment',            
            'status' => 'status'
        ];
        
        if(!empty($traderCode)){  
            $params = $request->all();
            $storeItems['traderCode'] = $traderCode;
            foreach($dbFields AS $k => $v){
                $storeItems[$k] = !empty($params[$v]) ? $params[$v] : '';
            }

            /* Inserting receivable to DB */
            $flag = Marchant::create($storeItems);                      
            if(!empty($flag->id)){                
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Marchant created successfully'
                ];
            }
            else{
                $apiResponse += [
                    'statusCode' => 9002,
                    'message' => 'Marchant insertion failed'                
                ];
            }
        }
        else{
            $apiResponse += [
                'statusCode' => 9001,
                'message' => 'Trader code is not valid'                
            ];
        }   
        return response()->json($apiResponse,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Marchant  $marchant
     * @return \Illuminate\Http\Response
     */
    public function show(Marchant $marchant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Marchant  $marchant
     * @return \Illuminate\Http\Response
     */
    public function edit(Marchant $marchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMarchantRequest  $request
     * @param  \App\Models\Api\Marchant  $marchant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarchantRequest $request, Marchant $marchant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Marchant  $marchant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marchant $marchant)
    {
        //
    }
}
