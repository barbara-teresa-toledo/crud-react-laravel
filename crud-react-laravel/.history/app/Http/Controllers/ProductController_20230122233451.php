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
      Product::create($request->post());

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
      'endereco' => 'required'
    ]);

    try {

      $product->fill($request->post())->update();

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
