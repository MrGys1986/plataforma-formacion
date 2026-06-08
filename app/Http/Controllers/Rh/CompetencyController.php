<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;

class CompetencyController extends Controller
{
    public function index()
    {
        // TODO: normalizar el catálogo de competencias institucionales.
        return view('rh.competencies.index');
    }
}
