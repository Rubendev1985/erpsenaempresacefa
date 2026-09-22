<?php

namespace Modules\Apicola\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Apicola\Entities\Apiario;
use Modules\Apicola\Entities\Colmena;

class ApiarioController extends Controller
{
    /**
     * Muestra la vista principal del módulo de Apiarios cargando datos reales de la BD.
     */
    public function index(Request $request)
    {
        // Si no existen apiarios registrados, sembramos el apiario oficial base del SENA La Angostura
        if (Apiario::count() === 0) {
            Apiario::create([
                'nombre_apiario' => 'Apiario SENA La Angostura',
                'latitud' => 2.68570000,
                'longitud' => -75.32410000,
                'estado' => 'Activo',
            ]);
        }

        $apiarios = Apiario::withCount('colmenas')->orderBy('id_apiarios', 'desc')->get();

        $apiariosFormatted = $apiarios->map(function ($item) {
            return [
                'id' => $item->id_apiarios,
                'nombre' => $item->nombre_apiario,
                'responsable' => 'Sergio Barrera',
                'lat' => (float) ($item->latitud ?? 2.6857),
                'lng' => (float) ($item->longitud ?? -75.3241),
                'colmenas' => $item->colmenas_count > 0 ? (int) $item->colmenas_count : 20,
                'estado' => $item->estado ?? 'Activo',
                'isSena' => (bool) (stripos($item->nombre_apiario, 'sena') !== false || stripos($item->nombre_apiario, 'angostura') !== false),
                'notas' => 'Apiario registrado en la base de datos MySQL (tabla apiario).'
            ];
        });

        $metrics = [
            'total' => Apiario::count(),
            'activos' => Apiario::activos()->count(),
            'inactivos' => Apiario::inactivos()->count(),
            'colmenas' => $apiariosFormatted->sum('colmenas'),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $apiariosFormatted,
                'metrics' => $metrics,
            ]);
        }

        return view('apicola::Admin.Apiario.index', compact('apiarios', 'apiariosFormatted', 'metrics'));
    }

    /**
     * Guarda un nuevo apiario en la base de datos MySQL (tabla apiario).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_apiario' => 'required|string|max:100',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'estado' => 'nullable|string|in:Activo,Inactivo',
            'colmenas' => 'nullable|integer|min:0',
        ]);

        $apiario = Apiario::create([
            'nombre_apiario' => trim($validated['nombre_apiario']),
            'latitud' => $validated['latitud'] ?? 2.6857,
            'longitud' => $validated['longitud'] ?? -75.3241,
            'estado' => $validated['estado'] ?? 'Activo',
        ]);

        $colmenasCount = isset($validated['colmenas']) ? (int) $validated['colmenas'] : 0;

        $formatted = [
            'id' => $apiario->id_apiarios,
            'nombre' => $apiario->nombre_apiario,
            'responsable' => 'Sergio Barrera',
            'lat' => (float) ($apiario->latitud ?? 2.6857),
            'lng' => (float) ($apiario->longitud ?? -75.3241),
            'colmenas' => $colmenasCount,
            'estado' => $apiario->estado,
            'isSena' => (bool) (stripos($apiario->nombre_apiario, 'sena') !== false || stripos($apiario->nombre_apiario, 'angostura') !== false),
            'notas' => 'Apiario registrado en la base de datos MySQL (tabla apiario).'
        ];

        $metrics = [
            'total' => Apiario::count(),
            'activos' => Apiario::activos()->count(),
            'inactivos' => Apiario::inactivos()->count(),
            'colmenas' => Apiario::count() * 20, // o suma calculada
        ];

        return response()->json([
            'success' => true,
            'message' => "¡Apiario \"{$apiario->nombre_apiario}\" guardado exitosamente en la base de datos!",
            'data' => $formatted,
            'metrics' => $metrics,
        ], 201);
    }

    /**
     * Actualiza un apiario existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $apiario = Apiario::findOrFail($id);

        $validated = $request->validate([
            'nombre_apiario' => 'required|string|max:100',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'estado' => 'nullable|string|in:Activo,Inactivo',
            'colmenas' => 'nullable|integer|min:0',
        ]);

        $apiario->update([
            'nombre_apiario' => trim($validated['nombre_apiario']),
            'latitud' => $validated['latitud'] ?? $apiario->latitud,
            'longitud' => $validated['longitud'] ?? $apiario->longitud,
            'estado' => $validated['estado'] ?? $apiario->estado,
        ]);

        $colmenasCount = isset($validated['colmenas']) ? (int) $validated['colmenas'] : 0;

        $formatted = [
            'id' => $apiario->id_apiarios,
            'nombre' => $apiario->nombre_apiario,
            'responsable' => 'Sergio Barrera',
            'lat' => (float) ($apiario->latitud ?? 2.6857),
            'lng' => (float) ($apiario->longitud ?? -75.3241),
            'colmenas' => $colmenasCount,
            'estado' => $apiario->estado,
            'isSena' => (bool) (stripos($apiario->nombre_apiario, 'sena') !== false || stripos($apiario->nombre_apiario, 'angostura') !== false),
            'notas' => 'Apiario actualizado en la base de datos MySQL (tabla apiario).'
        ];

        return response()->json([
            'success' => true,
            'message' => "¡Apiario \"{$apiario->nombre_apiario}\" actualizado correctamente!",
            'data' => $formatted,
            'metrics' => [
                'total' => Apiario::count(),
                'activos' => Apiario::activos()->count(),
                'inactivos' => Apiario::inactivos()->count(),
            ],
        ]);
    }

    /**
     * Alterna el estado de un apiario (Activo / Inactivo) directamente en MySQL.
     */
    public function toggleStatus(Request $request, $id)
    {
        $apiario = Apiario::findOrFail($id);
        $apiario->estado = ($apiario->estado === 'Activo') ? 'Inactivo' : 'Activo';
        $apiario->save();

        return response()->json([
            'success' => true,
            'nuevo_estado' => $apiario->estado,
            'message' => "Apiario \"{$apiario->nombre_apiario}\" marcado como {$apiario->estado}.",
            'metrics' => [
                'total' => Apiario::count(),
                'activos' => Apiario::activos()->count(),
                'inactivos' => Apiario::inactivos()->count(),
            ],
        ]);
    }
}
