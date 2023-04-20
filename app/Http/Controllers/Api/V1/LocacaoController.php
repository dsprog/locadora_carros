<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Locacao;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    protected $table = 'locacoes';
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Locacao $marca)
    {
        //
    }
    
    public function update(Request $request, Locacao $marca)
    {
        //
    }

    public function destroy(Locacao $marca)
    {
        //
    }
}
