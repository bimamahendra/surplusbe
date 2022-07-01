<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
            return response([
                "status_code"   => 200,
                "data"          => $category
            ], 200);
    }

    public function show($id)
    {   
        $getId = Category::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        $category = Category::find($getId->id);
            return response([
                "status_code"   => 200,
                "data"          => $category
            ], 200);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'      => 'required|string|unique:categories',
            'enable'    => 'required|boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataCategory = [
            'name'      => $req->input('name'),
            'enable'    => $req->input('enable')
        ];
        $category = Category::create($dataCategory);
        $category->save();

        return response([
            "status_code"       => 200,
            "status_message"    => "Data inserted"
        ], 200);
    }

    public function update(Request $req, $id)
    {   
        $getId = Category::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        
        $validator = Validator::make($req->all(), [
            'name'      => 'required|string',
            'enable'    => 'required|boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        $dataCategory = [
            'name'      => $req->input('name'),
            'enable'    => $req->input('enable')
        ];
        Category::where('id',$getId->id)->update($dataCategory);

        return response([
            "status_code"       => 200,
            "status_message"    => "Data updated"
        ], 200);
    }

    public function destroy($id)
    {
        $getId = Category::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        if(CategoryProduct::where('category_id',$getId->id)->exists()){
            return response([
                "status_code"    => 400,
                "status_message" => "Data cannot be deleted due to existence of related resource"
            ], 400);
        }
        Category::where('id',$getId->id)->delete();
        return response([
            "status_code"    => 200,
            "status_message" => "Data deleted"
        ], 200);
    }
}