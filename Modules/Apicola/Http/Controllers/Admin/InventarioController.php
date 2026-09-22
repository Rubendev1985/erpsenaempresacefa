<?php

namespace Modules\Apicola\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Apicola\Entities\Inventario;
use Modules\Apicola\Entities\InventarioMovimiento;
use Carbon\Carbon;

class InventarioController extends Controller
{
    /**
     * Muestra la vista principal del módulo de inventario.
     */
    public function index(Request $request)
    {
        $query = Inventario::with(['movimientos']);

        // Filtro de búsqueda por texto (nombre, código o responsable)
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria') && $request->categoria !== 'all') {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado') && $request->estado !== 'all') {
            $query->where('estado', $request->estado);
        }

        $items = $query->orderBy('id', 'asc')->get();

        // Cálculo de métricas dinámicas
        $totalElementos = Inventario::count();
        $totalCategorias = Inventario::distinct('categoria')->count('categoria');
        $totalUnidades = (int) Inventario::sum('cantidad');
        $bajoStock = Inventario::whereColumn('cantidad', '<=', 'stock_minimo')->count();

        // Movimientos del mes actual o periodo reciente
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Comprobar si hay movimientos en el mes actual o tomar agosto / últimos 30 días
        $movimientosQuery = InventarioMovimiento::query();
        $totalMovimientos = $movimientosQuery->count();
        $salidasMovimientos = (clone $movimientosQuery)->where('tipo', 'Salida')->count();
        $entradasMovimientos = (clone $movimientosQuery)->where('tipo', 'Entrada')->count();

        // Categorías disponibles
        $categorias = Inventario::select('categoria')
            ->distinct()
            ->pluck('categoria');

        $metrics = [
            'elementos_registrados' => $totalElementos,
            'categorias_count' => $totalCategorias,
            'unidades_existencia' => $totalUnidades,
            'bajo_stock' => $bajoStock,
            'movimientos_total' => $totalMovimientos,
            'movimientos_salidas' => $salidasMovimientos,
            'movimientos_entradas' => $entradasMovimientos,
            'mes_nombre' => 'Agosto',
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $items,
                'metrics' => $metrics,
                'categorias' => $categorias,
            ]);
        }

        return view('apicola::Admin.Inventario.index', compact('items', 'metrics', 'categorias'));
    }

    /**
     * Guarda un nuevo elemento en el inventario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:50|unique:apicola_inventarios,codigo',
            'categoria' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:Activo,Inactivo',
            'responsable' => 'required|string|max:150',
            'notas' => 'nullable|string',
        ]);

        $item = Inventario::create($validated);

        // Si se crea con cantidad > 0, registrar movimiento de entrada inicial
        if ($item->cantidad > 0) {
            InventarioMovimiento::create([
                'inventario_id' => $item->id,
                'tipo' => 'Entrada',
                'cantidad' => $item->cantidad,
                'stock_anterior' => 0,
                'stock_nuevo' => $item->cantidad,
                'motivo' => 'Registro inicial en el sistema',
                'responsable' => $item->responsable,
                'fecha' => now(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Elemento '{$item->nombre}' registrado con éxito.",
                'data' => $item->load('movimientos'),
            ], 201);
        }

        return redirect()->back()->with('success', "Elemento '{$item->nombre}' registrado.");
    }

    /**
     * Obtiene el detalle de un elemento con su historial de movimientos.
     */
    public function show($id)
    {
        $item = Inventario::with('movimientos')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    /**
     * Actualiza un elemento de inventario existente.
     */
    public function update(Request $request, $id)
    {
        $item = Inventario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:50|unique:apicola_inventarios,codigo,' . $item->id,
            'categoria' => 'required|string|max:100',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:Activo,Inactivo',
            'responsable' => 'required|string|max:150',
            'notas' => 'nullable|string',
        ]);

        $item->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Elemento '{$item->nombre}' actualizado con éxito.",
                'data' => $item->load('movimientos'),
            ]);
        }

        return redirect()->back()->with('success', "Elemento '{$item->nombre}' actualizado.");
    }

    /**
     * Alterna el estado (Activo / Inactivo) de un elemento sin eliminarlo.
     */
    public function toggleStatus($id)
    {
        $item = Inventario::findOrFail($id);
        $item->estado = ($item->estado === 'Inactivo') ? 'Activo' : 'Inactivo';
        $item->save();

        return response()->json([
            'success' => true,
            'message' => "Estado del elemento '{$item->nombre}' cambiado a {$item->estado}.",
            'data' => $item,
        ]);
    }

    /**
     * Registra un movimiento (Entrada, Salida o Ajuste) y actualiza el stock.
     */
    public function registrarMovimiento(Request $request, $id)
    {
        $item = Inventario::findOrFail($id);

        $validated = $request->validate([
            'tipo' => 'required|in:Entrada,Salida,Ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255',
            'responsable' => 'required|string|max:150',
            'fecha' => 'nullable|date',
        ]);

        $stockAnterior = $item->cantidad;
        $cantidad = (int) $validated['cantidad'];
        $tipo = $validated['tipo'];

        if ($tipo === 'Entrada') {
            $stockNuevo = $stockAnterior + $cantidad;
        } elseif ($tipo === 'Salida') {
            if ($cantidad > $stockAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede retirar {$cantidad} unidades. Stock disponible actual: {$stockAnterior}.",
                ], 422);
            }
            $stockNuevo = $stockAnterior - $cantidad;
        } else { // Ajuste
            $stockNuevo = $cantidad;
        }

        $item->cantidad = $stockNuevo;
        $item->save();

        $movimiento = InventarioMovimiento::create([
            'inventario_id' => $item->id,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $stockNuevo,
            'motivo' => $validated['motivo'],
            'responsable' => $validated['responsable'],
            'fecha' => $validated['fecha'] ? Carbon::parse($validated['fecha']) : now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Movimiento de {$tipo} registrado correctamente.",
            'data' => [
                'item' => $item->load('movimientos'),
                'movimiento' => $movimiento,
            ],
        ]);
    }
}
