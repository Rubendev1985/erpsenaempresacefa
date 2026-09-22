<?php

namespace Modules\Apicola\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Apicola\Entities\Inventario;
use Modules\Apicola\Entities\InventarioMovimiento;
use Carbon\Carbon;

class ApicolaInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas para evitar duplicados si se reejecuta el seeder
        InventarioMovimiento::truncate();
        Inventario::query()->forceDelete();

        // Solo el traje y los guantes según solicitud del usuario
        $items = [
            [
                'nombre' => 'Traje de protección completo (Overol)',
                'codigo' => 'INV-IND-001',
                'categoria' => 'Indumentaria',
                'cantidad' => 12,
                'stock_minimo' => 5,
                'responsable' => 'Sergio Barrera',
                'estado' => 'Activo',
                'notas' => 'Overoles con careta integrada tipo astronauta y ventilación.',
            ],
            [
                'nombre' => 'Guantes de cuero con manga reforzada',
                'codigo' => 'INV-IND-007',
                'categoria' => 'Indumentaria',
                'cantidad' => 14,
                'stock_minimo' => 6,
                'responsable' => 'Diana Carolina Vargas',
                'estado' => 'Activo',
                'notas' => 'Guantes de vaqueta suave con mangas elásticas de lona.',
            ],
        ];

        $createdElements = [];
        foreach ($items as $item) {
            $createdElements[$item['codigo']] = Inventario::create($item);
        }

        // Movimientos correspondientes a los trajes y guantes
        $movementsData = [
            // Movimientos del Traje (INV-IND-001)
            [
                'code' => 'INV-IND-001',
                'tipo' => 'Entrada',
                'cantidad' => 5,
                'motivo' => 'Recepción de overoles nuevos para aprendices',
                'responsable' => 'Sergio Barrera',
                'fecha' => '2026-08-05 09:15:00',
            ],
            [
                'code' => 'INV-IND-001',
                'tipo' => 'Salida',
                'cantidad' => 2,
                'motivo' => 'Asignación a instructores para jornada de cosecha',
                'responsable' => 'Sergio Barrera',
                'fecha' => '2026-08-06 08:00:00',
            ],

            // Movimientos de los Guantes (INV-IND-007)
            [
                'code' => 'INV-IND-007',
                'tipo' => 'Entrada',
                'cantidad' => 6,
                'motivo' => 'Ingreso de dotación de guantes de vaqueta',
                'responsable' => 'Diana Carolina Vargas',
                'fecha' => '2026-08-12 11:30:00',
            ],
            [
                'code' => 'INV-IND-007',
                'tipo' => 'Salida',
                'cantidad' => 2,
                'motivo' => 'Dotación a pasantes técnicos',
                'responsable' => 'Diana Carolina Vargas',
                'fecha' => '2026-08-26 14:00:00',
            ],
        ];

        foreach ($movementsData as $m) {
            $inv = $createdElements[$m['code']] ?? null;
            if ($inv) {
                InventarioMovimiento::create([
                    'inventario_id' => $inv->id,
                    'tipo' => $m['tipo'],
                    'cantidad' => $m['cantidad'],
                    'stock_anterior' => $inv->cantidad,
                    'stock_nuevo' => $inv->cantidad,
                    'motivo' => $m['motivo'],
                    'responsable' => $m['responsable'],
                    'fecha' => Carbon::parse($m['fecha']),
                ]);
            }
        }
    }
}
