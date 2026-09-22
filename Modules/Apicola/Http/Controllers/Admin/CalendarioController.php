<?php

namespace Modules\Apicola\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    /**
     * Muestra la vista principal del módulo de Calendario Floral.
     */
    public function index()
    {
        return view('apicola::Admin.Calendario.index');
    }
}
