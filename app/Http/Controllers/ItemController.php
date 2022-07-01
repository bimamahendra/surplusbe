<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Image;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $item = DB::table('category_products')
        ->join('product_images','product_images.id', '=', 'category_products.id')
        ->join('products','category_products.product_id', '=', 'products.id')
        ->join('categories','category_products.category_id', '=', 'categories.id')
        ->join('images','product_images.image_id', '=', 'images.id')
        ->select('category_products.id as id', 'products.name as product_name', 'products.description as description', 'categories.name as category_name', 'images.file as file')
        ->get();
        ;
            return response([
                "status_code"   => 200,
                "data"          => $item
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
        $item = DB::table('category_products')
        ->where('category_products.id',$getId->id)
        ->join('product_images','product_images.id', '=', 'category_products.id')
        ->join('products','category_products.product_id', '=', 'products.id')
        ->join('categories','category_products.category_id', '=', 'categories.id')
        ->join('images','product_images.image_id', '=', 'images.id')
        ->select('products.name as product_name', 'products.description as description', 'categories.name as category_name', 'images.file as file')
        ->get();
        ;
            return response([
                "status_code"   => 200,
                "data"          => $item
            ], 200);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'product_id'    => 'required|numeric|exists:products,id',
            'category_id'   => 'required|numeric|exists:categories,id',
            'image_id'      => 'required|numeric|exists:images,id',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $product = Product::where('id',$req->input('product_id'))->where('enable',1)->first();
        if(!$product){
            return response([
                "status_code"    => 400,
                "status_message" => "Product ID ".$req->input('product_id')." is disabled"
            ], 400);
        }

        $category = Category::where('id',$req->input('category_id'))->where('enable',1)->first();
        if(!$category){
            return response([
                "status_code"    => 400,
                "status_message" => "Category ID ".$req->input('category_id')." is disabled"
            ], 400);
        }

        $image = Image::where('id',$req->input('image_id'))->where('enable',1)->first();
        if(!$image){
            return response([
                "status_code"    => 400,
                "status_message" => "Image ID ".$req->input('image_id')." is disabled"
            ], 400);
        }

        if(
            CategoryProduct::where('product_id',$req->input('product_id'))->where('category_id',$req->input('category_id'))->exists() ||
            ProductImage::where('product_id',$req->input('product_id'))->where('image_id',$req->input('image_id'))->exists()
            ){
            return response([
                "status_code"       => 400,
                "status_message"    => "Data is already in the collection"
            ], 400);
        }

        $dataCatPro = [
            'product_id'    => $req->input('product_id'),
            'category_id'   => $req->input('category_id'),
        ];

        $dataImgPro = [
            'product_id'    => $req->input('product_id'),
            'image_id'      => $req->input('image_id')
        ];
        
        $catpro = CategoryProduct::create($dataCatPro);
        $imgpro = ProductImage::create($dataImgPro);
        $catpro->save();
        $imgpro->save();

        return response([
            "status_code"       => 200,
            "status_message"    => "Data inserted"
        ], 200);
    }

    public function update(Request $req, $id)
    {   
        $getIdCP = CategoryProduct::where('id',$id)->first();
        if(!$getIdCP){
            return response([
                "status_code"    => 400,
                "status_message" => "ID Data not found in Category Product"
            ], 400);
        }

        $getIdPI = ProductImage::where('id',$id)->first();
        if(!$getIdPI){
            return response([
                "status_code"    => 400,
                "status_message" => "ID Data not found in Product Image"
            ], 400);
        }
        
        $validator = Validator::make($req->all(), [
            'product_id'    => 'required|numeric|exists:products,id',
            'category_id'   => 'required|numeric|exists:categories,id',
            'image_id'      => 'required|numeric|exists:images,id',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $product = Product::where('id',$req->input('product_id'))->where('enable',1)->first();
        if(!$product){
            return response([
                "status_code"    => 400,
                "status_message" => "Product ID ".$req->input('product_id')." is disabled"
            ], 400);
        }

        $category = Category::where('id',$req->input('category_id'))->where('enable',1)->first();
        if(!$category){
            return response([
                "status_code"    => 400,
                "status_message" => "Category ID ".$req->input('category_id')." is disabled"
            ], 400);
        }

        $image = Image::where('id',$req->input('image_id'))->where('enable',1)->first();
        if(!$image){
            return response([
                "status_code"    => 400,
                "status_message" => "Image ID ".$req->input('image_id')." is disabled"
            ], 400);
        }

        if(
            CategoryProduct::where('product_id',$req->input('product_id'))->where('category_id',$req->input('category_id'))->exists() ||
            ProductImage::where('product_id',$req->input('product_id'))->where('image_id',$req->input('image_id'))->exists()
            ){
            return response([
                "status_code"       => 400,
                "status_message"    => "Data is already in the collection"
            ], 400);
        }

        $dataCatPro = [
            'product_id'    => $req->input('product_id'),
            'category_id'   => $req->input('category_id'),
        ];

        $dataImgPro = [
            'product_id'    => $req->input('product_id'),
            'image_id'      => $req->input('image_id')
        ];
        
        CategoryProduct::where('id',$getIdCP->id)->update($dataCatPro);
        ProductImage::where('id',$getIdPI->id)->update($dataImgPro);

        return response([
            "status_code"       => 200,
            "status_message"    => "Data updated"
        ], 200);
    }

    public function destroy($id)
    {
        $getIdCP = CategoryProduct::where('id',$id)->first();
        if(!$getIdCP){
            return response([
                "status_code"    => 400,
                "status_message" => "ID Data not found in Category Product"
            ], 400);
        }

        $getIdPI = ProductImage::where('id',$id)->first();
        if(!$getIdPI){
            return response([
                "status_code"    => 400,
                "status_message" => "ID Data not found in Product Image"
            ], 400);
        }
        
        CategoryProduct::where('id',$getIdCP->id)->delete();
        ProductImage::where('id',$getIdPI->id)->delete();
        
        return response([
            "status_code"    => 200,
            "status_message" => "Data deleted"
        ], 200);
    }
}