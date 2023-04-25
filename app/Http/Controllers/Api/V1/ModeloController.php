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

    public function index(Request $request)
    {
        $modelos = $this->modelo;
        $marca = false;
        if ($request->has('fields')){
            $fields = $request->fields;

            $fieldsArr = explode(',', $fields);
            if(in_array('marca_id', $fieldsArr)){
                $marca = true;
            }

            $modelos = $modelos->selectRaw($fields);
        }

        if ($request->has('search')){
            $search = $request->search;

            $filtro = explode(';', $search);

            foreach($filtro as $k => $v){
                $searchArr = explode(':', $v);
                if(isset($searchArr[2])){
                    $modelos = $modelos->where(
                        $searchArr[0], 
                        $searchArr[1], 
                        $searchArr[2]
                    );
                }
            }
        }

        if($marca){        
            if ($request->has('marca_fields')){
                $marca_fields = $request->marca_fields;
                $modelos = $modelos->with('marca:id,'.$marca_fields);
            }else{
                $modelos = $modelos->with('marca');
            }
        }

        return $modelos->get();
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
