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

    public function show(Marca $marca)
    {
        return $marca;
    }
    
    public function update(Request $request, Marca $marca)
    {
        //
    }

    public function destroy(Marca $marca)
    {
        $marca = $marca->delete();
        return $marca;
    }
}
