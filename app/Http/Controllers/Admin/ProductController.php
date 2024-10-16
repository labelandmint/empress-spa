<?php

namespace App\Http\Controllers\Admin;
Use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index(){
        $products = Product::where('status',1)->get();
        // return $products;
        return view('admin.products.index',compact('products'));
    }

    public function store(Request $request)
    {
        // // Validate the request
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string|max:1000',
        //     'quantity' => 'required|integer|min:1',
        //     'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Validate the image
        // ]);

         // Prepare data for insertion
        $data = $request->all();
        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store the file and get the file path
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $this->uploadImage($file, $fileName);
        }
        // return $data;
        if($request->id){
            $Product = Product::find($request->id);
            if(!$request->hasFile('image') && (!isset($request->image_url) || $request->image_url == '')){
                $data['image'] = null;
            }
            $Product->update($data);
            // Optionally redirect or return a response
        return redirect()->back()->with('success', 'Product updated successfully.');
        }else{
           // Create the product
            Product::create($data);
            // Optionally redirect or return a response
            return redirect()->back()->with('success', 'Product added successfully.');
        }
    }

    public function archive(Request $request,$id)
    {
        $Product = Product::find($id);

        if($Product->status == 1){
            $Product->status = 3;
            $Product->archived_at = now();
        }else{
            $Product->status = 1;
            $Product->archived_at = null;
        }
        $Product->save();

        return redirect()->back()->with('success','Product archived successfully.');
        
    }


    private function uploadImage($file, $fileName)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        // Return the path where the file is stored
        return url('/') . '/public/images/'. $filename;
    }

    // In ProductController.php
    public function filter(Request $request) {
        $status = $request->input('status');

        $products = Product::where('status', $status)->get();

        // Return a partial view or JSON response
        return view('admin.products.partials.product_list', compact('products'))->render();
    }


}
