<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Models\Api\Product;
use App\Models\Api\product_tags AS PrdTags;
use App\Models\Api\ProductMeta;
use App\Models\Api\ProductTags;

class ProductController extends Controller
{
    /* ================ HELPER FUNCTIONS A ==================== */
    /**
     * Filtering model resources to eleminate disired values
     */
    public function filterResponse($values){
        $exculdedKeys = ['id','traderCode','productId','created_at','updated_at'];
        foreach($exculdedKeys AS $k){
            if(isset($values->$k)){
                unset($values->$k);
            }
            else if(isset($values[$k])){
                unset($valus[$k]);
            }
        }
        return $values;
    }
    /* ================ HELPER FUNCTIONS Z ==================== */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiResponse = [];
        $products = [];
        $results = Product::leftJoin('product_metas','products.id','=','product_metas.productId')
            ->orderBy('products.id','desc')
            ->get([
                'products.*',
                'product_metas.id AS productMetaId',
                'product_metas.*'
            ]);

        if ($results->count()) {
            $items = $results->all();
            foreach ($items as $product) {
                $products[] = [
                    'id' => $product->id,
                    'productCode' => !empty($product->productCode) ? $product->productCode : '',
                    'productTitle'=>!empty($product->productTitle) ? $product->productTitle : '',                    
                    'wholesellPrice' => !empty($product->wholesellPrice) ? $product->wholesellPrice : '',
                    'retailPrice' => !empty($product->retailPrice) ? $product->retailPrice : '',
                    'specialPrice' => !empty($product->specialPrice) ? $product->specialPrice : '',
                    'initialUnit' => !empty($product->initialUnit) ? $product->initialUnit : '',
                    'lotNumber' => !empty($product->lotNumber) ? $product->lotNumber : '',
                    'tags' => !empty($product->tags) ? $product->tags : ''
                ];
            }
            $apiResponse = [
                'apiStatus' => 2002,
                'payload' => $products,
                'message' => 'Product fethed successfully'
            ];
        } else {
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
        return response()->json([
            'locaton' => 'form create'
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $apiResponse = [];
        $traderCode = $request->header('trader');

        if (!empty($traderCode)) {
            $params = $request->all();
            $productInfo['traderCode'] = $traderCode;

            $productInfo['productCode'] = 'AKSM'.strtoupper(substr($params['prdModel'],1,3));        
            if(!empty($params['prdTitle'])){
                $productInfo['productTitle'] = $params['prdTitle'];
            }
            if(!empty($params['prdWholeSalePrice'])){
                $productInfo['wholesellPrice'] = $params['prdWholeSalePrice'];
            }
            if(!empty($params['prdRetailPrice'])){
                $productInfo['retailPrice'] = $params['prdRetailPrice'];
            }
            if(!empty($params['prdSpecialPrice'])){
                $productInfo['specialPrice'] = $params['prdSpecialPrice'];
            }
            if(!empty($params['prdCurrentStock'])){
                $productInfo['initialUnit'] = $params['prdCurrentStock'];
            }
            if(!empty($params['prdLotNumber'])){
                $productInfo['lotNumber'] = $params['prdLotNumber'];
            }
            if(!empty($params['prdTags'])){
                $productInfo['tags'] = $params['prdTags'];
            }

            /* Inserting product to DB */
            $flag = Product::create($productInfo);
            if (!empty($flag->id) && is_numeric($flag->id)) {
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Product initialized successfully'
                ];
                $productMeta['productId'] = $flag->id;
                $productMeta['description'] = !empty($params['prdDescription']) ? $params['prdDescription'] : '';
                $productMeta['model'] = !empty($params['prdModel']) ? $params['prdModel'] : '';
                $productMeta['origin'] = !empty($params['prdOrigin']) ? $params['prdOrigin'] : '';
                $productMeta['company'] = !empty($params['prdCompany']) ? $params['prdCompany'] : '';
                $productMeta['variant'] = !empty($params['prdVariant']) ? $params['prdVariant'] : '';
                $productMeta['comment'] = !empty($params['prdComment']) ? $params['prdComment'] : '';
                $productMeta['otherMeta'] = !empty($params['prdOther']) ? $params['prdOther'] : '';
                $productMeta['status'] = 'Created';
                $productMeta['flag'] = 1;

                $metaFlag = ProductMeta::create($productMeta);
                if (!empty($metaFlag->id) && is_numeric($metaFlag->id)) {
                    $apiResponse['metaFlag'] = 'Product information saved successfully';
                } else {
                    $apiResponse['metaFlag'] = 'Product meta values NOT SAVED. Please check';
                }
            } else {
                $apiResponse += [
                    'statusCode' => 9001,
                    'message' => 'Product insertion failed'
                ];
            }
        } else {
            $apiResponse += [
                'statusCode' => 9002,
                'message' => 'Trader id is not valid'
            ];
        }

        return response()->json($apiResponse, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        $traderCode = $request->header('trader');
        $apiResponse = [];
        if(!empty($traderCode)){
            unset($product->traderCode);
            $productMeta =  $productTags = [];

            $tagRes = PrdTags::where(['productId'=>$product->id])->get();
            if($tagRes->count()){
                $productTags = $this->filterResponse($tagRes->first());
            }

            $metaRes = ProductMeta::where(['productId'=>$product->id])->get();       
            if($metaRes->count()){
                $productMeta = $this->filterResponse($metaRes->first());
            }
            $apiResponse = [
                'statusCode' => '2002',
                'message' => 'Fetching a single product',
                'product' => $product,
                'productTags' => $productTags,
                'productMeta' => $productMeta       
            ];
        }
        else{
            $apiResponse = [
                'statusCode' => '9001',
                'message' => 'Abandoned request'
            ];
        }
        return response()->json($apiResponse, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response()->json([
            'locaton' => 'form show single edit'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Api\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $traderId = $request->header('trader');
        $postFields = $request->all();
        $apiResponse = [];
        if (!empty($traderId) && !empty($postFields['productId'])) {
            unset($product->trader_id);
            $productInfo = [
                'product_title' => $postFields['prdTitle'],
                'wholesell_price' => !empty($postFields['prdWholeSalePrice']) ? $postFields['prdWholeSalePrice'] : '',
                'retail_price' => !empty($postFields['prdRetailPrice']) ? $postFields['prdRetailPrice'] : 1,
                'special_price' => !empty($postFields['prdSpecialPrice']) ? $postFields['prdSpecialPrice'] : 1,
                'unit' => !empty($postFields['prdCurrentStock']) ? $postFields['prdCurrentStock'] : '',
                'lot_id' => !empty($postFields['prdLotNumber']) ? $postFields['prdLotNumber'] : '',
                'tags' => !empty($postFields['prdTags']) ? $postFields['prdTags'] : ''
            ];
            $productMeta = [
                'description' => !empty($postFields['prdDescription']) ? $postFields['prdDescription'] : '',
                'model' => !empty($postFields['prdModel']) ? $postFields['prdModel'] : '',
                'origin' => !empty($postFields['prdOrigin']) ? $postFields['prdOrigin'] : '',
                'company' => !empty($postFields['prdCompany']) ? $postFields['prdCompany'] : '',
                'variant' => !empty($postFields['prdVariant']) ? $postFields['prdVariant'] : '',
                'comment' => !empty($postFields['prdComment']) ? $postFields['prdComment'] : '',
                'otherMeta' => !empty($postFields['prdOther']) ? $postFields['prdOther'] : ''
            ];

            $productFlag = Product::where(['id' => $postFields['productId'],'trader_id' => $traderId])->update($productInfo);            
            if ($productFlag) {
                $apiResponse = [
                    'statusCode' => 2002
                ];

                $productMetaFlag = ProductMeta::where(['product_id' => $postFields['productId'],'trader_id' => $traderId])->update($productMeta);
                if ($productMetaFlag) {
                    $apiResponse['message'] = 'Product updated successfully.';
                } else {
                    $apiResponse['statusCode'] = 2998;
                    $apiResponse['message'] = 'Product updated but meta valus not saved properly';
                }
            } else {
                $apiResponse = [
                    'statusCode' => 9001,
                    'message' => 'Product update failed'
                ];
            }
        } else {
            $apiResponse = [
                'statusCode' => '9001',
                'message' => 'Abandoned request'
            ];
        }
        return response()->json($apiResponse,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return response()->json([
            'locaton' => 'single f*** '
        ], 200);
    }

    public function getTags(Request $request, Response $response)
    {
        $apiResponse = [];
        $traderCode = $request->header('trader');

        if (!empty($traderCode)) {
            $tagRes = ProductTags::get();
            if ($tagRes->count()) {
                foreach ($tagRes as $tags ) {
                    $apiResponse[] = [
                        'pid' => $tags->productId,
                        'tags' => $tags->tags
                    ];
                }
            } else {
                $apiResponse = [
                    'statusCode' => '9002',
                    'message' => 'No tags avalable'
                ];
            }
        }

        return response()->json($apiResponse, 200);
    }
    public function storeTags(Request $request, Response $response)
    {
        $apiResponse = [];
        $traderCode = $request->header('trader');

        if (!empty($traderCode)) {
            $tags = $request->all();
            $flag = ProductTags::create([
                'productId' => $tags['productId'],
                'tags' => $tags['tags']
            ]);

            if ($flag->id) {
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Product tag saved successfully'
                ];
            } else {
                $apiResponse = [
                    'statusCode' => '9002',
                    'message' => 'Something went wrong! Product tag is not inserted'
                ];
            }
        }

        return response()->json($apiResponse, 200);
    }
}
