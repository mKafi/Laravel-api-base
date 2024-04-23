<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receivable\StoreReceivableRequest;
use App\Http\Requests\Api\Receivable\UpdateReceivableRequest;
use App\Models\Api\Receivable;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request AS HttpRequest;

class ReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $apiResponse = [];
        $receivables = [];
        $qRes = Receivable::orderBy('id','desc')->get();
        if($qRes->count()){   
            $revRes = $qRes->all();
            foreach($revRes AS $recevable){
                $receivables[] = [
                    'rid' => $recevable->id,                    
                    'client' => !empty($recevable->name) ? $recevable->name : '',
                    'address' =>  !empty($recevable->address) ? $recevable->address : '',
                    'reference' => !empty($recevable->reference) ? $recevable->reference : '',
                    'contact'=> $recevable->contact,                 
                    'amount' => !empty($recevable->amount) ? $recevable->amount : '',
                    'dueDate' => !empty($recevable->dueDate) ? $recevable->dueDate : '',                    
                    'type' => !empty($recevable->type) ? $recevable->type : '',
                    'comment' => !empty($recevable->comment) ? $recevable->comment : '',
                    'status' => !empty($recevable->status) ? $recevable->status : ''                
                ];
            }
            $apiResponse = [
                'apiStatus' => 2002,
                'receivables' => $receivables,
                'message' => 'Recevable records fetched successfully'
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReceivableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceivableRequest $request)
    {        
        $apiResponse = [];
        $receivable = [];
        $traderCode = $request->header('trader');
        if(!empty($traderCode)){  
            $params = $request->all();                        
            $receivable['traderCode'] = $traderCode;
            $receivable['client'] = !empty($params['rcvClient']) ? $params['rcvClient'] : '';            
            $receivable['address'] = !empty($params['rcvAddress']) ? $params['rcvAddress'] : '';
            $receivable['reference'] = !empty($params['rcvReference']) ? $params['rcvReference'] : '';
            $receivable['contact'] = !empty($params['rcvContact']) ? $params['rcvContact'] : '';
            $receivable['amount'] = !empty($params['rcvAmount']) ? $params['rcvAmount'] : '';
            $receivable['dueDate'] = !empty($params['rcvDueDate']) ? $params['rcvDueDate'] : '';
            $receivable['type'] = !empty($params['rcvType']) ? $params['rcvType'] : '';
            $receivable['comment'] = !empty($params['rcvComment']) ? $params['rcvComment'] : '';
            $receivable['status'] = !empty($params['rcvStatus']) ? $params['rcvStatus'] : '';
            
            /* Inserting receivable to DB */
            $flag = Receivable::create($receivable);                      
            if(!empty($flag->id)){                
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Receivable initialized successfully'
                ];
            }
            else{
                $apiResponse += [
                    'statusCode' => 9002,
                    'message' => 'Receivable insertion failed'                
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
     * @param  \App\Models\Api\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function show(HttpRequest $httpRequest, Receivable $receivable)
    {
        $receivableId = $receivable->id;        
        $traderCode = $httpRequest->header('trader');
        if($receivable->traderCode === $traderCode){            
            $apiResponse = [
                'statusCode' => '2002',
                'message' => 'Fetching a single receivable',
                'receivable' => [ 
                    'client' => $receivable->client,
                    'address' => $receivable->address,
                    'reference' => $receivable->reference,
                    'contact' => $receivable->contact,
                    'amount' => $receivable->amount,
                    'dueDate' => $receivable->dueDate,
                    'type' => $receivable->type,
                    'comment' => $receivable->comment,
                    'status' => $receivable->status
                ]      
            ];
        }
        else{
            $apiResponse = [
                'statusCode' => '9001',
                'message' => 'Abandoned request'                
            ];
        }
        // Log api response
        return response()->json($apiResponse,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function edit(Receivable $receivable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReceivableRequest  $request
     * @param  \App\Models\Api\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReceivableRequest $request, Receivable $receivable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receivable $receivable)
    {
        //
    }
}
