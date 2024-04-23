<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Trader\StoreTraderRequest;
use App\Http\Requests\Api\Trader\UpdateTraderRequest;
use Illuminate\Support\Facades\Validator;

use App\Models\Api\Trader;

class TraderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Trader::all();
        return response()->json([
            'status' => TRUE, 
            'message' => 'Getting all traders',
            'role' => $roles
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTraderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTraderRequest $request)
    {
        $validateTrader = Validator::make($request->all(),[
            'title' => 'required'
        ]);

        if($validateTrader->fails()){
            return response()->json([
                'status' => FALSE, 
                'message' => 'Validation error',
                'role' => $validateTrader->errors()
            ],401);
        }
        
        $trader = Trader::create($request->all());
        return response()->json([
            'status' => TRUE, 
            'message' => 'New trader created',
            'role' => $trader
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function show(Trader $trader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function edit(Trader $trader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTraderRequest  $request
     * @param  \App\Models\Api\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTraderRequest $request, Trader $trader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trader $trader)
    {
        //
    }
}
