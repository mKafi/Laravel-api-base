<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StoreCustomerRequest;
use App\Http\Requests\Api\Customer\UpdateCustomerRequest;
use App\Models\Api\Customer;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request AS HttpRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiResponse = [];
        $customers = [];
        $qRes = Customer::orderBy('id','desc')->get();
        if($qRes->count()){   
            $custRes = $qRes->all();
            foreach($custRes AS $customer){
                $customers[] = [
                    'cid' => $customer->id,                    
                    'name' => !empty($customer->name) ? $customer->name : '',
                    'address' =>  !empty($customer->address) ? $customer->address : '',
                    'phone'=> $customer->phone,                 
                    'father' => !empty($customer->father) ? $customer->father : '',
                    'mother' => !empty($customer->mother) ? $customer->mother : '',
                    'reference' => !empty($customer->reference) ? $customer->reference : '',
                    'email' => !empty($customer->email) ? $customer->email : '',
                    'nid' => !empty($customer->nid) ? $customer->nid : '',
                    'mediaUrl' => !empty($customer->mediaUrl) ? $customer->mediaUrl : '',
                    'profileInfo' => !empty($customer->profileInfo) ? $customer->profileInfo : '',
                    'photoUrl' => !empty($customer->photoUrl) ? $customer->photoUrl : '',
                    'tags' => !empty($customer->tags) ? $customer->tags : '',
                    'status' => !empty($customer->status) ? $customer->status : '',
                ];
            }
            $apiResponse = [
                'apiStatus' => 2002,
                'customers' => $customers,
                'message' => 'Customer records fetched successfully'
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
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $httpRequest, StoreCustomerRequest $request)
    {        
        $apiResponse = [];
        $traderCode = $httpRequest->header('trader');  
        if(!empty($traderCode)){  
            $params = $request->all();             
            $custInfo['traderCode'] = $traderCode;
            $custInfo['name'] = !empty($params['cstName']) ? $params['cstName'] : '';
            $custInfo['address'] = !empty($params['cstAddress']) ? $params['cstAddress'] : '';
            $custInfo['phone'] = !empty($params['cstPhone']) ? $params['cstPhone'] : '';
            $custInfo['father'] = !empty($params['cstFather']) ? $params['cstFather'] : '';
            $custInfo['mother'] = !empty($params['cstMother']) ? $params['cstMother'] : '';
            $custInfo['reference'] = !empty($params['cstReference']) ? $params['cstReference'] : '';
            $custInfo['email'] = !empty($params['cstEmail']) ? $params['cstEmail'] : '';
            $custInfo['nid'] = !empty($params['cstNid']) ? $params['cstNid'] : '';            
            $custInfo['socialMeadiaUrl'] = !empty($params['cstSocialLink']) ? $params['cstSocialLink'] : '';
            $custInfo['profileInfo'] = !empty($params['cstPortfolio']) ? $params['cstPortfolio'] : '';
            $custInfo['photUrl'] = !empty($params['cstPhoto']) ? $params['cstPhoto'] : '';
            $custInfo['tags'] = !empty($params['cstTags']) ? $params['cstTags'] : '';
            $custInfo['status'] = !empty($params['cstStatus']) ? $params['cstStatus'] : '';
            $custInfo['comment'] = !empty($params['cstComment']) ? $params['cstComment'] : '';
            
            /* Inserting customer to DB */
            $flag = Customer::create($custInfo);                      
            if($flag){                
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Customer created successfully'
                ];
            }
            else{
                $apiResponse += [
                    'statusCode' => 9002,
                    'message' => 'Customer insertion failed'                
                ];
            }
        }
        else{
            $apiResponse += [
                'statusCode' => 9002,
                'message' => 'Trader code is not valid'                
            ];
        }        
        return response()->json($apiResponse,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(HttpRequest $httRequest, Customer $customer)
    {
        $saleId = $customer->id;        
        $traderCode = $httRequest->header('trader');
        if($customer->traderCode === $traderCode){            
            $apiResponse = [
                'statusCode' => '2002',
                'message' => 'Fetching a single customer',
                'customer' => [                        
                    'name' => $customer->name,
                    'address' => $customer->address,
                    'phone' => $customer->phone,
                    'father' => $customer->father,
                    'mother' => $customer->mother,
                    'reference' => $customer->reference,
                    'email' => $customer->email,
                    'nid' => $customer->nid,
                    'mediaLink' => $customer->socialMeadiaUrl,
                    'portfolio' => $customer->profileInfo,
                    'photoUrl' => $customer->photUrl,
                    'tags' => $customer->tags,
                    'comment' => $customer->comment,
                    'status' => $customer->status
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
     * @param  \App\Models\Api\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Api\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
