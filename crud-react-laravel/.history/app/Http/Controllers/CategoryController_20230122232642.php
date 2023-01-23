<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{

  public function index()
  {
    return Product::select('id', 'nome', 'telefone', 'endereco')->get();
  }

  public function store(Request $request)
  {
    $request->validate([
      'nome' => 'required',
      'telefone' => 'required',
      'endereco' => 'required'
    ]);

    try {
      $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
      Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
      Product::create($request->post() + ['image' => $imageName]);

      return response()->json([
        'message' => 'Product Created Successfully!!'
      ]);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json([
        'message' => 'Something goes wrong while creating a product!!'
      ], 500);
    }
  }

  public function show(Product $product)
  {
    return response()->json([
      'product' => $product
    ]);
  }

  public function update(Request $request, Product $product)
  {
    $request->validate([
      'nome' => 'required',
      'telefone' => 'required',
      'image' => 'nullable'
    ]);

    try {

      $product->fill($request->post())->update();

      if ($request->hasFile('image')) {

        // remove old image
        if ($product->image) {
          $exists = Storage::disk('public')->exists("product/image/{$product->image}");
          if ($exists) {
            Storage::disk('public')->delete("product/image/{$product->image}");
          }
        }

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
        $product->image = $imageName;
        $product->save();
      }

      return response()->json([
        'message' => 'Product Updated Successfully!!'
      ]);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json([
        'message' => 'Something goes wrong while updating a product!!'
      ], 500);
    }
  }

  public function destroy(Product $product)
  {
    try {

      if ($product->image) {
        $exists = Storage::disk('public')->exists("product/image/{$product->image}");
        if ($exists) {
          Storage::disk('public')->delete("product/image/{$product->image}");
        }
      }

      $product->delete();

      return response()->json([
        'message' => 'Product Deleted Successfully!!'
      ]);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json([
        'message' => 'Something goes wrong while deleting a product!!'
      ]);
    }
  }
}
