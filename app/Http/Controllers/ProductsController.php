<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    public function __construct(
        protected ProductResource $productResource
    ){

    }

    public function collection(array|object $resources): JsonResource
    {
        return $this->productResource->collection($resources);
    }

    public function resource(array|object $resource): JsonResource
    {
        return $this->productResource->make($resource);
    }

    public function index()
    {
        $product = Product::all();
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'product created successfully',
            'pro$product' => $product,
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $product = Product::find($id);
        $product->title = $request->title;
        $product->description = $request->description;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'product updated successfully',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'product deleted successfully',
            'product' => $product,
        ]);
    }


    // public function index()
    // {
    //     $products = Product::all();
    //     return view('products', compact('products'));
    // }

    // public function cart()
    // {
    //     return view('cart');
    // }

    // public function addToCart($id)
    // {
    //     $product = Product::findOrFail($id);

    //     $cart = session()->get('cart', []);

    //     if(isset($cart[$id])) {
    //         $cart[$id] ['quantity']++;
    //     } else {
    //         $cart[$id] = [
    //             "product_name" => $product->product_name,
    //             "photo" => $product->photo,
    //             "price" => $product->price,
    //             "quantity" => 1
    //         ];
    //     }

    //     session()->put('cart', $cart);
    //     return redirect()->back()->with('success', 'Product add to cart successfully');
    // }

    // public function update(Request $request)
    // {
    //     if($request->id && $request->quantity){
    //         $cart = session()->get('cart');
    //         $cart[$request->id] ["quantity"] = $request->quantity;
    //         session()->put('cart', $cart);
    //         session()->flash('success', 'Cart successfully updated');
    //     }
    // }

    // public function remove(Request $request)
    // {
    //     if($request->id) {
    //         $cart = session()->get('cart');
    //         if(isset($cart[$request->id])) {
    //             unset($cart[$request->id]);
    //             session()->put('cart', $cart);
    //         }
    //         session()->flash('success', 'Product removed successfully');
    //     }
    // }
}
