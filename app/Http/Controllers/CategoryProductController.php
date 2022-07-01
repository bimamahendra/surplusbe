<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryProductController extends Controller
{
    public function index()
    {
        $catpro = CategoryProduct::all();
            return response([
                "status_code"   => 200,
                "data"          => $catpro
            ], 200);
    }

    public function show($id)
    {   
        $getId = CategoryProduct::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        $catpro = CategoryProduct::find($getId->id);
            return response([
                "status_code"   => 200,
                "data"          => $catpro
            ], 200);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'product_id'    => 'required|numeric|exists:products,id',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataCatPro = [
            'product_id'    => $req->input('product_id'),
            'category_id'   => $req->input('category_id')
        ];
        if(CategoryProduct::where('product_id',$req->input('product_id'))->where('category_id',$req->input('category_id'))->exists()){
            return response([
                "status_code"       => 400,
                "status_message"    => "Data is already in the collection"
            ], 400);
        }
        $catpro = CategoryProduct::create($dataCatPro);
        $catpro->save();

        return response([
            "status_code"       => 200,
            "status_message"    => "Data inserted"
        ], 200);
    }

    public function update(Request $req, $id)
    {   
        $getId = CategoryProduct::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        
        $validator = Validator::make($req->all(), [
            'product_id'    => 'required|numeric|exists:products,id',
            'category_id'   => 'required|numeric|exists:categories,id',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataCatPro = [
            'product_id'      => $req->input('product_id'),
            'category_id'     => $req->input('category_id')
        ];
        
        if(CategoryProduct::where('product_id',$req->input('product_id'))->where('category_id',$req->input('category_id'))->exists()){
            return response([
                "status_code"       => 400,
                "status_message"    => "Data is already in the collection"
            ], 400);
        }
        
        CategoryProduct::where('id',$getId->id)->update($dataCatPro);

        return response([
            "status_code"       => 200,
            "status_message"    => "Data updated"
        ], 200);
    }

    public function destroy($id)
    {
        $getId = CategoryProduct::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        CategoryProduct::where('id',$getId->id)->delete();
        return response([
            "status_code"    => 200,
            "status_message" => "Data deleted"
        ], 200);
    }
}