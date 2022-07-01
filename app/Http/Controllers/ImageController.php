<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File; 

class ImageController extends Controller
{
    public function index()
    {
        $image = Image::all();
            return response([
                "status_code"   => 200,
                "data"          => $image
            ], 200);
    }

    public function show($id)
    {   
        $getId = Image::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        $image = Image::find($getId->id);
            return response([
                "status_code"   => 200,
                "data"          => $image
            ], 200);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'      => 'required|string|unique:images',
            'file'      => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'enable'    => 'required|boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        if($req->hasFile('file')){
            $image = $req->file('file');
            $destinationPath    = 'image/';
            $profileImage       = date('YmdHis').".".$image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }

        $dataImage = [
            'name'      => $req->input('name'),
            'file'      => $profileImage,
            'enable'    => $req->input('enable')
        ];

        $res = Image::create($dataImage);
        $res->save();

        return response([
            "status_code"       => 200,
            "status_message"    => "Data inserted"
        ], 200);
    }

    public function update(Request $req)
    {   
        $getId = Image::where('id',$req->input('id'))->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        if(ProductImage::where('product_id',$getId->id)->exists()){
            return response([
                "status_code"    => 400,
                "status_message" => "Data cannot be deleted due to existence of related resource"
            ], 400);
        }
        
        $validator = Validator::make($req->all(), [
            'name'      => 'string',
            'enable'    => 'boolean',
        ]
        );

        if($validator->fails()){
            return response([
                "status_code"    => 400,
                "status_message" => $validator->errors()->first()
            ], 400);
        }

        if($req->hasFile('file')){
            $image = $req->file('file');
            $destinationPath    = 'image/';
            $profileImage       = date('YmdHis').".".$image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $dataImage = [
            'name'      => $req->input('name'),
            'file'      => $profileImage,
            'enable'    => $req->input('enable')
        ];
        if(File::exists(public_path($destinationPath.$getId->file))){
            File::delete(public_path($destinationPath.$getId->file));
        }
        }else{
            $dataImage = [
                'name'      => $req->input('name'),
                'enable'    => $req->input('enable')
            ];
        }
        Image::where('id',$getId->id)->update($dataImage);

        return response([
            "status_code"       => 200,
            "status_message"    => "Data updated"
        ], 200);
    }

    public function destroy($id)
    {   
        $destinationPath    = 'image/';
        $getId = Image::where('id',$id)->first();
        if(!$getId){
            return response([
                "status_code"    => 400,
                "status_message" => "Data not found"
            ], 400);
        }
        if(ProductImage::where('product_id',$getId->id)->exists()){
            return response([
                "status_code"    => 400,
                "status_message" => "Data cannot be deleted due to existence of related resource"
            ], 400);
        }
        if(File::exists(public_path($destinationPath.$getId->file))){
            File::delete(public_path($destinationPath.$getId->file));
            Image::where('id',$getId->id)->delete();
        }
        return response([
            "status_code"    => 200,
            "status_message" => "Data deleted"
        ], 200);
    }
}