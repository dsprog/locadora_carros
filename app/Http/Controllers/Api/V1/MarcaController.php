<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::all();
        return $marcas;
    }

    public function store(Request $request)
    {
        $marca = Marca::create($request->all());
        return $marca;
    }

    public function show($id)
    {
        $marca = Marca::find($id);
        if ($marca === null){
            return response()->json(['msg' => 'nada foi com sucesso!'], 404);
        }
        return $marca;
    }
    
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        if ($marca === null){
            return response()->json(['msg' => 'nada foi com sucesso!'], 404);
        }
        $marca->update($request->all());
        return $marca;
    }

    public function destroy($id)
    {        
        $marca = Marca::find($id);
        if ($marca === null){
            return response()->json(['msg' => 'nada foi com sucesso!'], 404);
        }
        $marca->delete();
        return ['msg' => 'Removido com sucesso!'];
    }
}
