<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Modelo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function index()
    {
        $modelo = $this->modelo->with('marca')->get();
        return $modelo;
    }

    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $file = $request->file("imagem");
        $imagem = $file->store('modelos', 'public');

        $data = [
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ];

        $modelo = $this->modelo->create($data);
        return $modelo;
    }

    public function show($id)
    {
        $modelo  = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }
        return $modelo;
    }

    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            foreach($modelo->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $modelo->feedback());
        }else{
            $request->validate($modelo->rules(), $modelo->feedback());
        }

        $file = $request->file("imagem");
        $imagem = $modelo->imagem;
        if($file){
            Storage::disk('public')->delete($imagem);
            $imagem = $file->store('modelos', 'public');
        }

        $modelo->fill($request->all());
        $modelo->imagem = $imagem;
        $modelo->save();

        return $modelo;
    }

    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return ['msg' => 'Removido com sucesso!'];
    }
}
