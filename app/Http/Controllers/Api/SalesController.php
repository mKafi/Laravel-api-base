<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Sales\StoreSalesRequest;
use App\Http\Requests\Api\Sales\UpdateSalesRequest;
use App\Models\Api\Customer;
use App\Models\Api\Sales;
use App\Models\Api\Product;
use App\Models\Api\Invoice;
use App\Models\Api\InvoicePorduct;
use stdClass;

class SalesController extends Controller
{
    /**
     * Helper functions A
     */
    public function createNewCustomer($custInfo){
        $flag = Customer::where(['phone'=>$custInfo['phone']])->first();
        if(!$flag){
            $custFlag = Customer::create($custInfo);            
            // Log for new user
        }
    }
    /* === Z === */
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiResponse = [];
        $sales = [];
        $results = Sales::leftJoin('invoices', 'sales.id', '=', 'invoices.saleId')->orderBy('sales.id','desc')->get([
            'sales.*',
            'sales.id AS salesId',
            'invoices.id AS invoiceId',
            'invoices.*',            
        ])->toArray();
        
        if(!empty($results)){                                    
            foreach($results AS $sale){
                $sales[] = [
                    'sid' => $sale['salesId'],
                    'date' => $sale['sellingDate'],
                    'name' => !empty($sale['name']) ? $sale['name'] : '',
                    'address' =>  !empty($sale['address']) ? $sale['address'] : '',
                    'phone'=>!empty($sale['phone']) ? $sale['phone'] : '',                    
                    'invoiceCode' => $sale['invoiceCode'],
                    'invoiceTotal' => $sale['grandTotal'],
                    'paid' => $sale['paid'],
                    'due' => $sale['due'],
                    'reference' => $sale['reference'],
                    'salesAgent' => $sale['salesAgent']
                ];
            }
            $apiResponse = [
                'apiStatus' => 2002,
                'sales' => $sales,
                'message' => 'Sales records fetched successfully'
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
     * @param  \App\Http\Requests\StoreSalesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalesRequest $request)
    {        
        $flag = FALSE;
        $apiResponse = [];
        $statusLog = [];
        $errors = [];
        $traderCode = $request->header('trader');
        $salesInfo = $invoiceInfo = $invoiceProducts = [];  
        $params = $request->all();
        if(!empty($traderCode)){                   
            $sellingDate = date("Y-m-d H:i:s");
            $maxID = Sales::max("id");            
            $salesInfo = [
                'traderCode' => $traderCode,
                'sellingDate' => $sellingDate,
                'name' => !empty($params['cstName']) ? trim($params['cstName']) : '',
                'address' => !empty($params['cstAddrss']) ? trim($params['cstAddrss']) : '',
                'reference' => !empty($params['cstReference']) ? trim($params['cstReference']) : '',  
                'phone' => $params['cstPhone'],
                'comment' => !empty($params['ordComment']) ? trim($params['ordComment']) : '',                
                'salesPoint' => !empty($params['salesPoint']) ? trim($params['salesPoint']) : '',
                'salesAgent' => !empty($params['salesAgent']) ? trim($params['salesAgent']) : '',
                'status' => 'Order placed'
            ];

            $salesFlag = Sales::create($salesInfo);
            if(!empty($salesFlag->id) && is_numeric($salesFlag->id)){
                $statusLog[] = [
                    'status' => 'Sales info saved successfully. Id is: '.$salesFlag->id
                ]; 
                
                $grandTotal = 0;
                $invoiceCode = 'AM'.date("ymd").'C'.substr($params['cstPhone'],-9).'S'.sprintf("%04d",($salesFlag->id));
                $subtotal = !empty($params['subtotal']) ? trim($params['subtotal']) : 0;
                $tax = !empty($params['tax']) ? trim($params['tax']) : 0;
                $previousDue = !empty($params['previousDue']) ? trim($params['previousDue']) : 0;
                $discount = !empty($params['invDiscount']) ? trim($params['invDiscount']) : 0;
                $grandTotal = (($subtotal+$tax+$previousDue)-$discount);
                $paid = ($params['ordPaid'] - $params['ordChange']);
                $due = ($grandTotal - $paid);
                                
                $invoiceInfo = [
                    'saleId' => $salesFlag->id,                    
                    'invoiceCode' => $invoiceCode,
                    'subTotal' => $subtotal,
                    'tax' => $tax,
                    'previousDue' => $previousDue,
                    'discount' => $discount,
                    'grandTotal' => $grandTotal,
                    'paid' => $paid,
                    'due' => $due,
                    'nextPaymentDate' => NULL,
                    'comment' => '',
                    'status' => 'Invoice initiated'
                ];

                $invoiceFlag = Invoice::create($invoiceInfo);
                if(!empty($invoiceFlag->id) && is_numeric($invoiceFlag->id)){
                    $statusLog[] = [
                        'status' => 'Invoice info saved successfully. Id is: '.$invoiceFlag->id
                    ]; 
                    
                    if(!empty($params['prdList'])){
                        $products = explode('_',$params['prdList']);
                        if(!empty($products) && is_array(($products))){                    
                            $prdIds = [];
                            foreach($products AS $product){
                                $prdAttr = explode('|',$product);
                                if(isset($prdAttr[0]) && is_numeric($prdAttr[0])){
                                    $prdIds[] = $prdAttr[0];                            
                                    $invoiceProducts['pid_'.$prdAttr[0]] = [                                        
                                        'invoiceId' => $invoiceFlag->id,
                                        'productId' => $prdAttr[0],
                                        'itemTitle' => '',
                                        'itemModel' => '',
                                        'qty' => !empty($prdAttr[1]) ? $prdAttr[1] : -1,
                                        'unitPrice' => '',
                                        'itemTax' => 0,
                                        'status' => ''
                                    ];
                                }
                            }
        
                            $ordProducts = Product::whereIn('id',$prdIds)->get(); 
                            if(!empty($ordProducts->count())){                                
                                foreach($ordProducts AS $prd){
                                    $invoiceProducts['pid_'.$prd['id']]['itemTitle'] = $prd['productTitle'];     
                                    $invoiceProducts['pid_'.$prd['id']]['unitPrice'] = $prd['retailPrice'];                        
                                }
                            }
        
                            $invoiceProductFlag = InvoicePorduct::insert($invoiceProducts);
                            if($invoiceProductFlag){
                                $statusLog[] = [
                                    'status' => 'Invoice product info saved successfully. Total item inserted: '.count($invoiceProducts)
                                ];  
                                
                                // Creating new customer
                                $this->createNewCustomer([
                                    'traderCode' => $traderCode,
                                    'name' => $salesInfo['name'],
                                    'address' => $salesInfo['address'],
                                    'phone' => $salesInfo['phone'],
                                    'reference' => $salesInfo['reference'],
                                    'status' => 'Customer initiated'
                                ]);
                            }
                            else{
                                $flag = TRUE;
                                $errors['invoiceProductError'] = 'Invoice product model insertion failed!';
                            }
                        }
                    }
                    else{
                        $errors['prdListError'] = 'Invalid product list posted from Client side.';
                    }
                }
                else{
                    $flag = TRUE;
                    $errors['invoiceInitiationError'] = 'Invoice model insertion failed!';
                }
                
            }
            else{
                $flag = TRUE;
                $errors['salesInitiationError'] = 'Sales model insertion failed!';
            }

            
            /* LOG the following array as reference */
            /* 
            [                
                'params' => $params,                
                'statusLog' => $statusLog,
                'errors' => $errors
            ]; 
            */ 
            if($flag == FALSE){
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Order saved successfully'
                ];
            }
            else{
                echo '<pre>'; print_r($errors); echo '</pre>'; 
                exit;
            }
        }
        else{
            $apiResponse += [
                'statusCode' => 9002,
                'message' => 'Trader id is not valid'                
            ];
        }
        return response()->json($apiResponse,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Sales $sale)
    {
        $saleId = $sale->id;
        $traderId = $request->header('trader');

        $apiResponse = [];        
        if(!empty($traderId)){            
            $saleRes = Sales::leftJoin('invoices', 'sales.id', '=', 'invoices.saleId')
                ->where('sales.id', '=', $saleId)               
                ->get([
                    'sales.*',
                    'invoices.id AS invoiceId',
                    'invoices.*',
                ]);
            
            if($saleRes->count()){                         
                $result = $saleRes->first();
                $apiResponse = [
                    'statusCode' => '2002',
                    'message' => 'Fetching a single sales',
                    'sales' => [
                        'salesPkId' => $result->id,
                        'invoicePkId' => $result->invoiceId,
                        'invoiceCode' => $result->invoiceCode,
                        'sellingDate' => $result->sellingDate,
                        'name' => $result->name,
                        'address' => $result->address,
                        'reference' => $result->reference,
                        'phone' => $result->phone,
                        'comment' => $result->comment,
                        'salesPoint' => $result->salesPoint,
                        'salesAgent' => $result->salesAgent,
                        'status' => $result->status,
                        'subTotal' => $result->subTotal,
                        'tax' => $result->tax,
                        'previousDue' => $result->previousDue,
                        'discount' => $result->discount,
                        'grandTotal' => $result->grandTotal,
                        'paid' => $result->paid,
                        'due' => $result->due,
                        'nextPaymentDate' => $result->nextPaymentDate
                    ]      
                ];
            }           
        }
        else{
            $apiResponse = [
                'statusCode' => '9001',
                'message' => 'Abandoned request'                
            ];
        }
        return response()->json($apiResponse,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalesRequest  $request
     * @param  \App\Models\Api\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesRequest $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        //
    }

    /**
     * $invoiceId. Requested invoice id (Pk)
     */
    public function getInvoice(Request $request, $invoiceId){
        $traderCode = $request->header('trader'); 
        $apiResponse = [];
        $error = [];
        if(isset($traderCode)){
            $invoiceRes = Invoice::where(['id'=>$invoiceId])->get([
                'id',               
                'invoiceCode',
                'subTotal',
                'tax',
                'previousDue',
                'discount',
                'grandTotal',
                'paid',
                'due',
                'nextPaymentDate',
                'comment',
                'status'
            ]);         
            if($invoiceRes->count()){
                $invoiceInfo = $invoiceRes->first();
                unset($invoiceInfo->trader_id);
                $invoiceProdRes = InvoicePorduct::where(['invoiceId'=>$invoiceId])->get([
                    'id',
                    'productId',
                    'itemTitle',
                    'itemModel',
                    'qty',
                    'unitPrice',
                    'itemTax',
                    'status'
                ]);
                if($invoiceProdRes->count()){                
                    $invoiceProducts = $invoiceProdRes->all();
                    $apiResponse = [
                        'statusCode' => '2002',
                        'message' => 'Fetching invoice information',
                        'invoiceInfo' => $invoiceInfo,
                        'invoiceItems' => $invoiceProducts
                    ];
                    // LOG output response
                }
                else{
                    $error['inovoiceError'] = 'Error on invoice product query';
                }
            }
            else{
                $error['inovoiceError'] = 'Error on invoice query';
            }

            if(count($error)){
                $apiResponse = [
                    'statusCode' => '9002',
                    'message' => 'Something went wrong on data processing'
                ]; 
                // LOG Error           
            }
        }
        else{
            $apiResponse = [
                'statusCode' => '9001',
                'message' => 'Abandoned request'                
            ];
        }
        return response()->json($apiResponse,200); 
    }
}
