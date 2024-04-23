<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Response $response)
    {
        $apiResponse = [];
        $categories = [];
        $results = Category::get();
        if ($results->count()){
            $categories = $results->toArray();
            $apiResponse = [
                'apiStatus' => 2002,
                'payload' => $categories,
                'message' => 'Categories fetched successfully'
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $apiResponse = [];
        $errors = [];
        $traderCode = $request->header('trader');
        if (!empty($traderCode)) {
            $params = $request->all();
            $catFlag = Category::create($params);
            if(!empty($catFlag->id) && is_numeric($catFlag->id)){
                $apiResponse += [
                    'statusCode' => 2002,
                    'message' => 'Category saved successfully'
                ];
            }
            else{
                $apiResponse += [
                    'statusCode' => 2003,
                    'message' => 'Category is not saved!'
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
     * @param  \App\Models\Api\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
