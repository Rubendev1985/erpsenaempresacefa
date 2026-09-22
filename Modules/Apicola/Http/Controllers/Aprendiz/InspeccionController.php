<?php

namespace Modules\Apicola\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Apicola\Entities\Inspeccion;
use Modules\Apicola\Entities\Colmena;

class InspeccionController extends Controller
{
    /**
     * Muestra la vista de Registro e Inspección de colmenas para el Aprendiz.
     */
    public function index(Request $request)
    {
        $selectedColmena = $request->get('colmena', 'COL-013');
        $colmenas = Colmena::orderBy('code', 'asc')->get();
        return view('apicola::Aprendiz.Colmenas.inspeccion', compact('selectedColmena', 'colmenas'));
    }

    /**
     * Inspecciones de una colmena específica por código.
     */
    public function byColmena($code)
    {
        $selectedColmena = $code;
        $colmenas = Colmena::orderBy('code', 'asc')->get();
        return view('apicola::Aprendiz.Colmenas.inspeccion', compact('selectedColmena', 'colmenas'));
    }

    /**
     * Almacenar una nueva inspección / visita realizada por el Aprendiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:30|unique:apicola_inspecciones,registration_number',
            'colmena_code' => 'required|string|max:30',
            'date' => 'required|date',
            'responsable' => 'required|string|max:150',
            'general_state' => 'required|in:Bueno,Regular,Malo',
            'honey_presence' => 'required|in:Bueno,Regular,Malo',
            'harvested_honey_kg' => 'nullable|numeric|min:0',
            'queen_seen' => 'nullable|boolean',
            'queen_marked' => 'nullable|boolean',
            'queen_color' => 'nullable|string|max:50',
            'diseases' => 'nullable|array',
            'tasks' => 'nullable|array',
            'feed_supplied' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ]);

        $inspeccion = Inspeccion::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Visita registrada con éxito.',
                'data' => $inspeccion
            ], 201);
        }

        return redirect()->back()->with('success', 'Visita registrada con éxito.');
    }
}
