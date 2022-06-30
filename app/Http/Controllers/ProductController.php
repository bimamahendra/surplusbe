<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();
            return response([
                "status_code"   => 200,
                "data"          => $product
            ], 200);
    }

    public function show($id)
    {   
        $getId = Product::where('id',$id)->get();
        if($getId->count() < 1){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        $product = Product::find($id);
            return response([
                "status_code"   => 200,
                "data"          => $product
            ], 200);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'          => 'required|string|unique:products',
            'description'   => 'required|string',
            'enable'        => 'required|boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataProduct = [
            'name'          => $req->input('name'),
            'description'   => $req->input('description'),
            'enable'        => $req->input('enable')
        ];
        $product = Product::create($dataProduct);
        $product->save();

        return response([
            "status_code"       => 200,
            "status_message"    => "Data inserted"
        ], 200);
    }

    public function update(Request $req, $id)
    {   
        $getId = Product::where('id',$id)->get();
        if($getId->count() < 1){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        
        $validator = Validator::make($req->all(), [
            'name'          => 'required|string',
            'description'   => 'required|string',
            'enable'        => 'required|boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataProduct = [
            'name'          => $req->input('name'),
            'description'   => $req->input('description'),
            'enable'        => $req->input('enable')
        ];
        Product::where('id',$id)->update($dataProduct);

        return response([
            "status_code"       => 200,
            "status_message"    => "Data updated"
        ], 200);
    }

    public function destroy($id)
    {
        $getId = Product::where('id',$id)->get();
        if($getId->count() < 1){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        if(CategoryProduct::where('product_id',$getId->id)->exists() || ProductImage::where('product_id',$getId->id)->exists()){
            return response([
                "status_code"    => 400,
                "status_message" => "Data cannot be deleted due to existence of related resource"
            ], 400);
        }
        Product::where('id',$getId->id)->delete();
        return response([
            "status_code"    => 200,
            "status_message" => "Data deleted"
        ], 200);
    }
}