<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClienteController extends Controller
{

  public function index()
  {
    return Cliente::select('id', 'nome', 'telefone', 'endereco')->get();
  }

  public function store(Request $request)
  {
    $request->validate([
      'nome' => 'required',
      'telefone' => 'required',
      'endereco' => 'required'
    ]);

    try {
      Cliente::create($request->post());

      return response()->json([
        'message' => 'Cliente cadastrado com sucesso!'
      ]);
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return response()->json([
        'message' => 'Não foi possível cadastrar novo cliente.'
      ], 500);
    }
  }

  public function show(Cliente $cliente)
  {
    return response()->json([
      'cliente' => $cliente
    ]);
  }

  public function update(Request $request, Cliente $cliente)
  {
    $request->validate([
      'nome' => 'required',
      'telefone' => 'required',
      'endereco' => 'required'
    ]);

    try {

      $cliente->fill($request->post())->update();

      return response()->json([
        'message' => 'Cliente editado com sucesso!'
      ]);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json([
        'message' => 'Não foi possível editar os dados do cliente.'
      ], 500);
    }
  }

  public function destroy(Cliente $cliente)
  {
    try {
      $cliente->delete();

      return response()->json([
        'message' => 'Cliente excluído com sucesso!'
      ]);
    } catch (\Exception $e) {
      \Log::error($e->getMessage());
      return response()->json([
        'message' => 'Não foi possível realizar a exclusão do cliente.'
      ]);
    }
  }
}
