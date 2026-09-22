<?php

namespace Modules\Apicola\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Apicola\Entities\Colmena;

class ColmenaController extends Controller
{
    /**
     * Display a listing of colmenas.
     */
    public function index(Request $request)
    {
        $query = Colmena::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%")
                  ->orWhere('apiario_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('apiario') && $request->apiario !== 'all') {
            $query->where('apiario_name', $request->apiario);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $colmenas = $query->orderBy('code', 'asc')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $colmenas,
                'metrics' => [
                    'total' => Colmena::count(),
                    'activas' => Colmena::activas()->count(),
                    'en_revision' => Colmena::enRevision()->count(),
                    'inactivas' => Colmena::inactivas()->count(),
                ]
            ]);
        }

        return view('apicola::Admin.Colmenas.index', compact('colmenas'));
    }

    /**
     * Store a newly created colmena in storage.
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
                'message' => 'Colmena creada exitosamente.',
                'data' => $colmena
            ], 201);
        }

        return redirect()->route('apicola.admin.colmenas.index')
            ->with('success', 'Colmena registrada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $colmena = Colmena::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $colmena
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $colmena = Colmena::findOrFail($id);
        return response()->json($colmena);
    }

    /**
     * Update the specified resource in storage.
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
     * Toggle status or deactivate/activate colmena.
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

    /**
     * Remove the specified colmena from storage.
     */
    public function destroy(Request $request, $id)
    {
        $colmena = Colmena::findOrFail($id);
        $code = $colmena->code;
        $colmena->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Colmena {$code} eliminada con éxito."
            ]);
        }

        return redirect()->back()->with('success', "Colmena {$code} eliminada.");
    }
}
