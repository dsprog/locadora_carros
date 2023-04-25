<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'marca_id'=>'required|integer|exists:marcas,id',
            'nome'=>'required|unique:modelos,nome,'.$this->id,
            'imagem'=>'required|file|mimes:png,jpg,jpeg,gif',
            'numero_portas'=>'required|integer|digits_between:1,5',
            'lugares'=>'required|integer|digits_between:1,20',
            'air_bag'=>'required|boolean',
            'abs'=>'required|boolean'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>':attribute obrigatório!',
            'nome.unique' => 'Marca já existe!'
        ];
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
