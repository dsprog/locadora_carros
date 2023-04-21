<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index()
    {
        $marca = $this->marca->all();
        return $marca;
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    public function show($id)
    {
        $marca  = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }
        return $marca;
    }

    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }

        $request->validate($marca->rules(), $marca->feedback());

        $marca->update($request->all());
        return $marca;
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }
        $marca->delete();
        return ['msg' => 'Removido com sucesso!'];
    }
}
