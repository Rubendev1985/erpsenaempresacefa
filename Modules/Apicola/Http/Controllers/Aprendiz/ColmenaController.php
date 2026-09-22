<?php

namespace Modules\Apicola\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Apicola\Entities\Colmena;
use Modules\Apicola\Entities\Apiario;
use Modules\Apicola\Entities\Inspeccion;

class ColmenaController extends Controller
{
    /**
     * Muestra la vista de Gestión de colmenas exclusiva para el Aprendiz.
     */
    public function index(Request $request)
    {
        $colmenas = Colmena::orderBy('code', 'asc')->get();

        $metrics = [
            'total' => Colmena::count(),
            'activas' => Colmena::activas()->count(),
            'en_revision' => Colmena::enRevision()->count(),
            'inactivas' => Colmena::inactivas()->count(),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $colmenas,
                'metrics' => $metrics,
            ]);
        }

        return view('apicola::Aprendiz.Colmenas.index', compact('colmenas', 'metrics'));
    }

    /**
     * Almacena una nueva colmena registrada por el Aprendiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:apicola_colmenas,code',
            'apiario_name' => 'required|string|max:150',
            'responsable' => 'required|string|max:150',
            'tipo_abeja' => 'nullable|string|max:150',
            'installation_date' => 'required|date',
            'last_visit_date' => 'nullable|date',
            'status' => 'required|in:Activa,En revisión,Inactiva',
            'notes' => 'nullable|string',
        ]);

        $colmena = Colmena::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Colmena {$colmena->code} guardada con éxito.",
                'data' => $colmena
            ], 201);
        }

        return redirect()->back()->with('success', "Colmena {$colmena->code} guardada.");
    }

    /**
     * Actualiza una colmena.
     */
    public function update(Request $request, $id)
    {
        $colmena = Colmena::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:apicola_colmenas,code,' . $colmena->id,
            'apiario_name' => 'required|string|max:150',
            'responsable' => 'required|string|max:150',
            'tipo_abeja' => 'nullable|string|max:150',
            'installation_date' => 'required|date',
            'last_visit_date' => 'nullable|date',
            'status' => 'required|in:Activa,En revisión,Inactiva',
            'notes' => 'nullable|string',
        ]);

        $colmena->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Colmena {$colmena->code} actualizada correctamente.",
                'data' => $colmena
            ]);
        }

        return redirect()->back()->with('success', "Colmena {$colmena->code} actualizada.");
    }

    /**
     * Alterna el estado de una colmena.
     */
    public function toggleStatus($id)
    {
        $colmena = Colmena::findOrFail($id);
        $colmena->status = ($colmena->status === 'Inactiva') ? 'Activa' : 'Inactiva';
        $colmena->save();

        return response()->json([
            'success' => true,
            'message' => "Estado de la colmena {$colmena->code} cambiado a {$colmena->status}.",
            'data' => $colmena
        ]);
    }
}
