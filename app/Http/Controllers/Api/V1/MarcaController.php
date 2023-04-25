<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    private $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index(Request $request)
    {
        $marcas = $this->marca;
        

        if ($request->has('fields')){
            $fields = $request->fields;
            $marcas = $marcas->selectRaw($fields);
        }

        if ($request->has('search')){
            $search = $request->search;

            $filtro = explode(';', $search);

            foreach($filtro as $k => $v){
                $searchArr = explode(':', $v);
                if(isset($searchArr[2])){
                    $marcas = $marcas->where(
                        $searchArr[0], 
                        $searchArr[1], 
                        $searchArr[2]
                    );
                }
            }
        }

        if ($request->has('modelo_fields')){
            $modelo_fields = $request->modelo_fields;
            $marcas = $marcas->with('modelos:id,marca_id,'.$modelo_fields);
        }else{
            $marcas = $marcas->with('modelos');
        }
        
        $marcas = $marcas->get();
        return $marcas;
    }

    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $file = $request->file("imagem");
        $imagem = $file->store('marcas', 'public');

        $data = [
            'nome' => $request->nome,
            'imagem' => $imagem
        ];

        $marca = $this->marca->create($data);
        return $marca;
    }

    public function show($id)
    {
        $marca  = $this->marca->with('modelos')->find($id);
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

        if($request->method() === 'PATCH'){
            $regrasDinamicas = [];

            foreach($marca->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        }else{
            $request->validate($marca->rules(), $marca->feedback());
        }

        $file = $request->file("imagem");
        $imagem = $marca->imagem;
        if($file){
            Storage::disk('public')->delete($imagem);
            $imagem = $file->store('marcas', 'public');
        }

        $marca->fill($request->all());
        $marca->imagem = $imagem;
        $marca->save();

        return $marca;
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro'=>'Não encontrado'], 404);
        }
        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();
        return ['msg' => 'Removido com sucesso!'];
    }
}
