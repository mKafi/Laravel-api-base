<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreproductStockRequest;
use App\Http\Requests\Api\Product\UpdateproductStockRequest;
use App\Models\Api\productStock;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreproductStockRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreproductStockRequest $request)
    {
        $apiResponse = [];
        $error = [];
        $traderCode = $request->header('trader');

        if (!empty($traderCode)) {
            $params = $request->all();
            $stockInfo['productId'] = $params['productId'] ?? $error['product'] = 'Product id is missing';
            $stockInfo['wholesellPrice'] = $params['wholesellPrice'] ?? $error['wholesellPrice'] = 'Wholesale price is empty';
            $stockInfo['retailPrice'] = $params['retailPrice'] ?? $error['retailPrice'] = 'Retail price is empty';
            $stockInfo['specialPrice'] = $params['specialPrice'] ?? $error['specialPrice'] = 'Special price is empty';
            $stockInfo['lotUnit'] = $params['lotUnit'] ?? $error['lotUnit'] = 'Lot unit count is empty';
            $stockInfo['lotPrice'] = $params['lotPrice'] ?? $error['lotPrice'] = 'Lot price should be define';
            $stockInfo['unitName'] = $params['unitName'] ?? $error['unitName'] = 'Unit name sholud be define';
            $stockInfo['lotNumber'] = $params['lotNumber'] ?? $error['lotNumber'] = 'Lot number is empty';
            $stockInfo['meta'] = $params['meta'] ?? '';
            $stockInfo['status'] = $params['status'] ?? $error['status'] = 'Invalid status';
            $stockInfo['flag'] = $params['flag'] ?? $error['flag'] = 'Invalid flag';

            /* Inserting product stock to DB */
            if (empty($error)) {
                $flag = productStock::create($stockInfo);
                if (!empty($flag->id) && is_numeric($flag->id)) {
                    $apiResponse += [
                        'statusCode' => 2002,
                        'message' => 'Product stock updated successfully'
                    ];
                }
                else
                {
                    $apiResponse += [
                        'statusCode' => 9002,
                        'message' => 'Product stock insertion failed'
                    ];
                    // LOG $flag
                }
            }
            else 
            {
                $apiResponse += [
                    'statusCode' => 9002,
                    'message' => 'Error on request'
                ]; 
                // LOG $error
            }
        }
        else
        {
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
     * @param  \App\Models\productStock  $productStock
     * @return \Illuminate\Http\Response
     */
    public function show(productStock $productStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\productStock  $productStock
     * @return \Illuminate\Http\Response
     */
    public function edit(productStock $productStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproductStockRequest  $request
     * @param  \App\Models\productStock  $productStock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateproductStockRequest $request, productStock $productStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\productStock  $productStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(productStock $productStock)
    {
        //
    }
}
