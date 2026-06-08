<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;

class RubricController extends Controller
{
    public function index()
    {
        // TODO: incorporar el catálogo institucional de rúbricas.
        return view('evaluator.rubrics.index');
    }
}
