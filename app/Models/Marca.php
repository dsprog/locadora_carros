<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome'=>'required|unique:marcas,nome,'.$this->id,
            'imagem'=>'required|file|mimes:png,jpg,jpeg,gif'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>':attribute obrigatório!',
            'nome.unique' => 'Marca já existe!'
        ];
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }

}
