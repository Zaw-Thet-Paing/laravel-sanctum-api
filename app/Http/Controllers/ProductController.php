<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try{
            return Product::all();
        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $product = Product::find($id);

            if(!$product){
                return response()->json([
                    'message'=> 'Product not found',
                    'status'=> 404
                ], 404);
            }

            return $product;
        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }

    public function store()
    {

        try{
            $validator = Validator::make(request()->all(), [
                'name'=> 'required',
                'price'=> 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'errors'=> $validator->errors(),
                    'status'=> 400
                ], 400);
            }

            $product = new Product();
            $product->name = request('name');
            $product->slug = request('slug');
            $product->description = request('description');
            $product->price = request('price');
            $product->save();

            return response()->json([
                'data'=> $product,
                'status'=> 201
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }

    public function update($id)
    {
        try{
            $validator = Validator::make(request()->all(), [
                'name'=> 'required',
                'price'=> 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'errors'=> $validator->errors(),
                    'status'=> 400
                ], 400);
            }

            $product = Product::find($id);
            if(!$product){
                return response()->json([
                    'message'=> 'Product not found',
                    'status'=> 404
                ], 404);
            }
            $product->name = request('name');
            $product->slug = request('slug');
            $product->description = request('description');
            $product->price = request('price');
            $product->update();

            return $product;

        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }

    public function destory($id)
    {
        try{
            $product = Product::find($id);
            if(!$product){
                return response()->json([
                    'message'=> 'Product not found',
                    'status'=> 404
                ], 404);
            }

            $product->delete();

            return $product;

        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }

    public function search($name)
    {
        try{
            return Product::where('name', 'like', '%'.$name.'%')->get();
        }catch(Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'status'=> 500
            ], 500);
        }
    }
}
