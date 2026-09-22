@extends('apicola::Admin.layout')

@section('title', 'Gestión de Inventario • SENA APÍCOLA')

@section('breadcrumb')
    <a href="{{ route('apicola.admin.inventario.index') }}">Inventario</a>
    <span>/</span>
    <span class="active-page">Gestión de inventario</span>
@endsection

@section('styles')
<style>
    /* Estilos específicos para la vista de Inventario */
    .ap-metric-card.border-green {
        border-left: 4px solid #059669;
    }
    .ap-metric-card.border-amber {
        border-left: 4px solid #f59e0b;
    }
    .ap-metric-card.border-red {
        border-left: 4px solid #ef4444;
    }
    .ap-metric-card-title {
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .ap-metric-card-num {
        font-size: 2.25rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 0.35rem;
    }
    .ap-metric-card-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* Sección de listado */
    .ap-list-section {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.75rem 1.75rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .ap-list-header {
        margin-bottom: 1.25rem;
    }
    .ap-list-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .ap-list-desc {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Barra de búsqueda y controles */
    .ap-controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 1.25rem;
    }
    .ap-controls-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        flex: 1;
    }
    .ap-search-box {
        position: relative;
        min-width: 260px;
        max-width: 320px;
        flex: 1;
    }
    .ap-search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }
    .ap-search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        font-size: 0.88rem;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
    }
    .ap-search-input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .ap-filter-dropdown {
        padding: 9px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        font-size: 0.88rem;
        color: #334155;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ap-filter-dropdown:focus {
        border-color: #059669;
    }

    .ap-controls-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ap-btn-categories {
        background: #ffffff;
        color: #059669;
        border: 1.5px solid #059669;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .ap-btn-categories:hover {
        background: #ecfdf5;
        transform: translateY(-1px);
    }

    .ap-btn-new-item {
        background: #059669;
        color: #ffffff;
        border: none;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
        transition: all 0.2s;
        text-decoration: none;
    }
    .ap-btn-new-item:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.35);
    }

    /* Tabla de inventario */
    .ap-table-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .ap-inv-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.88rem;
    }
    .ap-inv-table thead {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .ap-inv-table th {
        padding: 0.95rem 1.15rem;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    .ap-inv-table td {
        padding: 1rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }
    .ap-inv-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .ap-inv-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    /* Destaque de fila bajo stock mínimo */
    .ap-inv-table tbody tr.row-low-stock {
        background-color: #fffbeb !important;
    }
    .ap-inv-table tbody tr.row-low-stock:hover {
        background-color: #fef3c7 !important;
    }

    /* Badges de estado y advertencia */
    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .badge-status-pill.activo {
        background: #ecfdf5;
        color: #059669;
    }
    .badge-status-pill.inactivo {
        background: #f1f5f9;
        color: #64748b;
    }

    .badge-low-stock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #b45309;
        background: #fef3c7;
        padding: 2px 8px;
        border-radius: 6px;
        margin-left: 6px;
    }

    /* Acciones en la tabla */
    .ap-table-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ap-table-action-link {
        color: #059669;
        font-weight: 700;
        font-size: 0.84rem;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        text-decoration: none;
        transition: all 0.15s;
    }
    .ap-table-action-link:hover {
        color: #047857;
        text-decoration: underline;
    }
    .ap-table-action-link.secondary {
        color: #0284c7;
    }
    .ap-table-action-link.secondary:hover {
        color: #0369a1;
    }

    /* Footer de la tabla */
    .ap-table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0.5rem 0.25rem;
        font-size: 0.8rem;
        color: #64748b;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* Estilos del modal */
    .modal-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: -0.8rem;
        margin-bottom: 1.4rem;
    }
    .modal-form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .modal-form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }

    .type-pill-select {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
        margin-bottom: 1.25rem;
    }
    .type-pill-btn {
        padding: 10px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .type-pill-btn:hover {
        border-color: #94a3b8;
    }
    .type-pill-btn.active-entrada {
        background: #ecfdf5;
        border-color: #059669;
        color: #059669;
    }
    .type-pill-btn.active-salida {
        background: #fef2f2;
        border-color: #ef4444;
        color: #dc2626;
    }
    .type-pill-btn.active-ajuste {
        background: #f0f9ff;
        border-color: #0284c7;
        color: #0284c7;
    }

    /* Historial en modal de ver */
    .history-timeline-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        margin-top: 10px;
    }
    .history-timeline-table th {
        background: #f8fafc;
        padding: 8px 10px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-align: left;
    }
    .history-timeline-table td {
        padding: 9px 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
</style>
@endsection

@section('content')
    <!-- Encabezado de la vista con perfil apicultor/administrador -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">INVENTARIO</span>
            <h1 class="ap-view-main-title">Gestión de inventario</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar" style="background: #047857;">
                {{ Auth::user()?->initials ?? 'AC' }}
            </div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">
                    {{ Auth::user()?->full_name ?? 'Andrés Felipe Cuéllar' }}
                </span>
                <span class="ap-user-chip-role" style="display:flex; align-items:center; gap:4px;">
                    Rol: Administrador <i class="fas fa-chevron-down" style="font-size:0.65rem;"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- 4 Tarjetas de Métricas (Fieles al diseño) -->
    <div class="ap-metrics-grid">
        <!-- 1. Elementos Registrados -->
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">ELEMENTOS REGISTRADOS</div>
            <div class="ap-metric-card-num" id="cardTotalElementos">{{ $metrics['elementos_registrados'] }}</div>
            <div class="ap-metric-card-sub" id="cardSubCategorias">{{ $metrics['categorias_count'] == 1 ? 'En 1 categoría' : 'En ' . $metrics['categorias_count'] . ' categorías' }}</div>
        </div>

        <!-- 2. Unidades en Existencia -->
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">UNIDADES EN EXISTENCIA</div>
            <div class="ap-metric-card-num" id="cardTotalUnidades">{{ $metrics['unidades_existencia'] }}</div>
            <div class="ap-metric-card-sub">Suma de todas las cantidades</div>
        </div>

        <!-- 3. Bajo Stock Mínimo -->
        <div class="ap-metric-card border-red">
            <div class="ap-metric-card-title">BAJO STOCK MÍNIMO</div>
            <div class="ap-metric-card-num" id="cardBajoStock" style="color: #dc2626;">{{ $metrics['bajo_stock'] }}</div>
            <div class="ap-metric-card-sub">Requieren reposición</div>
        </div>

        <!-- 4. Movimientos de Agosto / Mes Actual -->
        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">MOVIMIENTOS DE {{ strtoupper($metrics['mes_nombre']) }}</div>
            <div class="ap-metric-card-num" id="cardTotalMovimientos">{{ $metrics['movimientos_total'] }}</div>
            <div class="ap-metric-card-sub" id="cardSubMovimientos">{{ $metrics['movimientos_salidas'] }} salidas · {{ $metrics['movimientos_entradas'] }} entradas</div>
        </div>
    </div>

    <!-- Sección: Listado de Inventario -->
    <div class="ap-list-section">
        <div class="ap-list-header">
            <h2 class="ap-list-title">Listado de inventario</h2>
            <p class="ap-list-desc">Herramientas y materiales de la unidad apícola. Las filas por debajo del stock mínimo aparecen destacadas.</p>
        </div>

        <!-- Controles: Búsqueda, Filtros y Acciones -->
        <div class="ap-controls-bar">
            <div class="ap-controls-left">
                <!-- Buscador -->
                <div class="ap-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="ap-search-input" placeholder="Buscar por nombre o código..." oninput="filtrarInventario()">
                </div>

                <!-- Filtro Categoría -->
                <select id="categoriaFilter" class="ap-filter-dropdown" onchange="filtrarInventario()">
                    <option value="all">Categoría: todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <!-- Filtro Estado -->
                <select id="estadoFilter" class="ap-filter-dropdown" onchange="filtrarInventario()">
                    <option value="all">Estado: todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>

            <div class="ap-controls-right">
                <button type="button" class="ap-btn-categories" onclick="abrirModalCategorias()">
                    Categorías
                </button>
                <button type="button" class="ap-btn-new-item" onclick="abrirModalNuevo()">
                    + Nuevo elemento
                </button>
            </div>
        </div>

        <!-- Tabla de Inventario -->
        <div class="ap-table-wrap">
            <table class="ap-inv-table">
                <thead>
                    <tr>
                        <th>ELEMENTO</th>
                        <th>CÓDIGO</th>
                        <th>CATEGORÍA</th>
                        <th>CANTIDAD</th>
                        <th>STOCK MÍNIMO</th>
                        <th>RESPONSABLE</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="inventarioTableBody">
                    <!-- Rellenado dinámicamente con JavaScript desde datos de Laravel -->
                </tbody>
            </table>
        </div>

        <!-- Pie de la Tabla -->
        <div class="ap-table-footer">
            <div>
                Mostrando <strong id="shownCount">{{ count($items) }}</strong> de <strong id="totalCount">{{ count($items) }}</strong> elementos
            </div>
            <div style="font-style: italic; color: #94a3b8;">
                Los elementos no se eliminan: se desactivan y conservan su historial de movimientos.
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- ==================== MODAL 1: NUEVO / EDITAR ELEMENTO (DISEÑO EXACTO IMAGEN 2) ==================== -->
    <div class="ap-modal-backdrop" id="modalElementoBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body" style="padding: 2rem 2.2rem 1.5rem;">
                <h2 class="ap-modal-title" id="modalElementoTitle" style="font-size: 1.55rem; font-weight: 900; color: #0f172a; margin-bottom: 0.35rem;">
                    Nuevo elemento
                </h2>
                <p class="modal-subtitle" id="modalElementoSubtitle">
                    Herramienta, material o insumo de la unidad apícola.
                </p>

                <form id="formElemento" onsubmit="guardarElementoForm(event)">
                    <input type="hidden" id="modalItemId" value="">

                    <!-- 1. NOMBRE -->
                    <div class="ap-form-field">
                        <label class="ap-field-label" for="inputNombre">NOMBRE</label>
                        <input type="text" id="inputNombre" class="ap-field-input" placeholder="Ahumador de acero inoxidable" required>
                    </div>

                    <!-- 2. CÓDIGO Y CATEGORÍA (2 Columnas) -->
                    <div class="modal-form-grid-2">
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="inputCodigo">CÓDIGO</label>
                            <input type="text" id="inputCodigo" class="ap-field-input" placeholder="INV-AHU-002" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="inputCategoria">CATEGORÍA</label>
                            <select id="inputCategoria" class="ap-field-input" required>
                                <option value="Herramientas">Herramientas</option>
                                <option value="Indumentaria">Indumentaria</option>
                                <option value="Insumos">Insumos</option>
                                <option value="Equipos">Equipos</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. CANTIDAD, STOCK MÍNIMO Y ESTADO (3 Columnas) -->
                    <div class="modal-form-grid-3">
                        <div class="ap-form-field" id="wrapCantidad">
                            <label class="ap-field-label" for="inputCantidad">CANTIDAD</label>
                            <input type="number" id="inputCantidad" class="ap-field-input" min="0" value="1" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="inputStockMinimo">STOCK MÍNIMO</label>
                            <input type="number" id="inputStockMinimo" class="ap-field-input" min="0" value="1" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="inputEstado">ESTADO</label>
                            <select id="inputEstado" class="ap-field-input" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. RESPONSABLE -->
                    <div class="ap-form-field">
                        <label class="ap-field-label" for="inputResponsable">RESPONSABLE</label>
                        <select id="inputResponsable" class="ap-field-input" required>
                            <option value="Diana Carolina Vargas">Diana Carolina Vargas</option>
                            <option value="Sergio Barrera">Sergio Barrera</option>
                            <option value="Andrés Felipe Cuéllar">Andrés Felipe Cuéllar</option>
                            <option value="Instructor Apícola SENA">Instructor Apícola SENA</option>
                        </select>
                    </div>

                    <!-- Texto de ayuda inferior -->
                    <p class="ap-field-hint" style="color: #94a3b8; font-size: 0.8rem; margin-top: 10px; line-height: 1.4;">
                        Cuando la cantidad quede por debajo del stock mínimo, el elemento se destaca en el listado.
                    </p>

                    <div class="ap-modal-footer" style="padding: 1.25rem 0 0; margin-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                        <button type="button" class="ap-btn-modal-cancel" onclick="cerrarModalElemento()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" style="background: #059669;" id="btnSubmitElemento">Guardar elemento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 2: REGISTRAR MOVIMIENTO (ENTRADA / SALIDA) ==================== -->
    <div class="ap-modal-backdrop" id="modalMovimientoBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body" style="padding: 2rem 2.2rem 1.5rem;">
                <h2 class="ap-modal-title" style="font-size: 1.55rem; font-weight: 900; color: #0f172a; margin-bottom: 0.35rem;">
                    Registrar movimiento
                </h2>
                <p class="modal-subtitle" id="movimientoSubtitle">
                    Actualiza la existencia física y guarda la trazabilidad del elemento.
                </p>

                <!-- Resumen de elemento seleccionado -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div id="movItemNombre" style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">Ahumador</div>
                        <div id="movItemCodigo" style="font-size: 0.78rem; color: #64748b;">INV-AHU-002</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Stock actual</div>
                        <div id="movItemStockActual" style="font-size: 1.35rem; font-weight: 900; color: #059669;">5</div>
                    </div>
                </div>

                <form id="formMovimiento" onsubmit="guardarMovimientoForm(event)">
                    <input type="hidden" id="movItemId" value="">
                    <input type="hidden" id="movTipo" value="Entrada">

                    <!-- Tipo de movimiento (Pills) -->
                    <label class="ap-field-label">TIPO DE MOVIMIENTO</label>
                    <div class="type-pill-select">
                        <button type="button" class="type-pill-btn active-entrada" id="btnTipoEntrada" onclick="setTipoMovimiento('Entrada')">
                            <i class="fas fa-arrow-down-left" style="font-size: 1.1rem;"></i>
                            <span>Entrada (+)</span>
                        </button>
                        <button type="button" class="type-pill-btn" id="btnTipoSalida" onclick="setTipoMovimiento('Salida')">
                            <i class="fas fa-arrow-up-right" style="font-size: 1.1rem;"></i>
                            <span>Salida (-)</span>
                        </button>
                        <button type="button" class="type-pill-btn" id="btnTipoAjuste" onclick="setTipoMovimiento('Ajuste')">
                            <i class="fas fa-sliders" style="font-size: 1.1rem;"></i>
                            <span>Ajuste (=)</span>
                        </button>
                    </div>

                    <!-- Cantidad y Fecha -->
                    <div class="modal-form-grid-2">
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="movCantidad">CANTIDAD</label>
                            <input type="number" id="movCantidad" class="ap-field-input" min="1" value="1" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="movFecha">FECHA</label>
                            <input type="date" id="movFecha" class="ap-field-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="ap-form-field">
                        <label class="ap-field-label" for="movMotivo">MOTIVO / CONCEPTO</label>
                        <input type="text" id="movMotivo" class="ap-field-input" placeholder="Ej: Reposición de almacén, práctica de campo..." required>
                    </div>

                    <!-- Responsable -->
                    <div class="ap-form-field">
                        <label class="ap-field-label" for="movResponsable">RESPONSABLE</label>
                        <select id="movResponsable" class="ap-field-input" required>
                            <option value="Diana Carolina Vargas">Diana Carolina Vargas</option>
                            <option value="Sergio Barrera">Sergio Barrera</option>
                            <option value="Andrés Felipe Cuéllar">Andrés Felipe Cuéllar</option>
                            <option value="Instructor Apícola SENA">Instructor Apícola SENA</option>
                        </select>
                    </div>

                    <div class="ap-modal-footer" style="padding: 1.25rem 0 0; margin-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                        <button type="button" class="ap-btn-modal-cancel" onclick="cerrarModalMovimiento()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" style="background: #059669;">Confirmar movimiento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 3: VER DETALLE E HISTORIAL ==================== -->
    <div class="ap-modal-backdrop" id="modalDetalleBackdrop">
        <div class="ap-modal-window large" style="max-width: 720px;">
            <div class="ap-modal-body" style="padding: 2rem 2.2rem 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" id="detCategoria">HERRAMIENTAS</span>
                        <h2 class="ap-modal-title" id="detNombre" style="margin-bottom: 0.2rem; font-size: 1.5rem;">
                            Ahumador de acero inoxidable
                        </h2>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;" id="detCodigo">INV-AHU-002</span>
                    </div>
                    <div id="detEstadoBadge">
                        <span class="badge-status-pill activo">Activo</span>
                    </div>
                </div>

                <!-- Tarjetas de datos rápidos -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 1.5rem;">
                    <div style="background: #f8fafc; padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Existencia</div>
                        <div style="font-size: 1.4rem; font-weight: 900; color: #0f172a;" id="detCantidad">5</div>
                    </div>
                    <div style="background: #f8fafc; padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Stock mínimo</div>
                        <div style="font-size: 1.4rem; font-weight: 900; color: #64748b;" id="detStockMinimo">3</div>
                    </div>
                    <div style="background: #f8fafc; padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0; grid-column: span 2;">
                        <div style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Responsable</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 4px;" id="detResponsable">Diana Carolina Vargas</div>
                    </div>
                </div>

                <!-- Historial de movimientos -->
                <div style="margin-bottom: 0.5rem;">
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>Historial de movimientos</span>
                        <button type="button" class="ap-table-action-link" style="font-size: 0.8rem;" onclick="abrirMovimientoDesdeDetalle()">+ Nuevo movimiento</button>
                    </h3>

                    <div style="max-height: 220px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <table class="history-timeline-table">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>TIPO</th>
                                    <th>CANTIDAD</th>
                                    <th>STOCK RESULTANTE</th>
                                    <th>MOTIVO</th>
                                    <th>RESPONSABLE</th>
                                </tr>
                            </thead>
                            <tbody id="detHistorialBody">
                                <!-- Filas de movimientos -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="ap-modal-footer" style="padding: 1.25rem 0 0; margin-top: 1.25rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                    <button type="button" id="btnToggleEstadoDetalle" class="ap-table-action-link" style="color: #ef4444;" onclick="toggleEstadoDesdeDetalle()">
                        Desactivar elemento
                    </button>
                    <button type="button" class="ap-btn-modal-cancel" onclick="cerrarModalDetalle()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL 4: CATEGORÍAS ==================== -->
    <div class="ap-modal-backdrop" id="modalCategoriasBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body" style="padding: 2rem 2.2rem 1.5rem;">
                <h2 class="ap-modal-title" style="font-size: 1.55rem; font-weight: 900; color: #0f172a; margin-bottom: 0.35rem;">
                    Categorías de inventario
                </h2>
                <p class="modal-subtitle">
                    Resumen de distribución de herramientas y suministros de la unidad apícola.
                </p>

                <div id="categoriasListContainer" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 1.5rem;">
                    <!-- Rellenado dinámicamente -->
                </div>

                <div class="ap-modal-footer" style="padding: 1.25rem 0 0; margin-top: 1.25rem; border-top: 1px solid #f1f5f9;">
                    <button type="button" class="ap-btn-modal-cancel" onclick="cerrarModalCategorias()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Inyección de datos iniciales del backend de Laravel
    let inventarioData = @json($items);
    let metricsData = @json($metrics);
    let selectedItemId = null;

    document.addEventListener('DOMContentLoaded', () => {
        renderInventarioTable();
        updateMetricsCards();
    });

    // ==================== RENDERIZADO DE TABLA Y FILTROS ====================
    function renderInventarioTable() {
        const search = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
        const cat = document.getElementById('categoriaFilter')?.value || 'all';
        const est = document.getElementById('estadoFilter')?.value || 'all';

        const filtered = inventarioData.filter(item => {
            const matchSearch = item.nombre.toLowerCase().includes(search) ||
                                item.codigo.toLowerCase().includes(search) ||
                                item.responsable.toLowerCase().includes(search);
            const matchCat = (cat === 'all') || (item.categoria === cat);
            const matchEst = (est === 'all') || (item.estado === est);

            return matchSearch && matchCat && matchEst;
        });

        const tbody = document.getElementById('inventarioTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        document.getElementById('shownCount').textContent = filtered.length;
        document.getElementById('totalCount').textContent = inventarioData.length;

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                        <i class="fas fa-search" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block;"></i>
                        No se encontraron elementos de inventario con los filtros aplicados.
                    </td>
                </tr>
            `;
            return;
        }

        filtered.forEach(item => {
            const tr = document.createElement('tr');
            const isLowStock = item.cantidad <= item.stock_minimo;
            if (isLowStock) {
                tr.className = 'row-low-stock';
            }

            const lowStockBadge = isLowStock 
                ? `<span class="badge-low-stock" title="Cantidad por debajo del stock mínimo (${item.stock_minimo})"><i class="fas fa-exclamation-triangle"></i> Bajo</span>` 
                : '';

            const statusClass = item.estado === 'Activo' ? 'activo' : 'inactivo';

            tr.innerHTML = `
                <td style="font-weight: 700; color: #0f172a;">
                    ${item.nombre}
                </td>
                <td style="font-weight: 600; color: #64748b;">
                    ${item.codigo}
                </td>
                <td>
                    <span style="background: #f1f5f9; color: #475569; padding: 3px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                        ${item.categoria}
                    </span>
                </td>
                <td>
                    <span style="font-weight: 800; font-size: 0.95rem; color: ${isLowStock ? '#b45309' : '#0f172a'};">
                        ${item.cantidad}
                    </span>
                    ${lowStockBadge}
                </td>
                <td style="color: #64748b; font-weight: 600;">
                    ${item.stock_minimo}
                </td>
                <td style="color: #334155;">
                    ${item.responsable}
                </td>
                <td>
                    <span class="badge-status-pill ${statusClass}">
                        ${item.estado}
                    </span>
                </td>
                <td>
                    <div class="ap-table-actions">
                        <button type="button" class="ap-table-action-link" onclick="abrirModalDetalle(${item.id})">Ver</button>
                        <button type="button" class="ap-table-action-link" onclick="abrirModalEditar(${item.id})">Editar</button>
                        <button type="button" class="ap-table-action-link" onclick="abrirModalMovimiento(${item.id})">Movimiento</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function filtrarInventario() {
        renderInventarioTable();
    }

    function updateMetricsCards() {
        const total = inventarioData.length;
        const categoriasUnicas = new Set(inventarioData.map(i => i.categoria)).size;
        const totalUnidades = inventarioData.reduce((acc, curr) => acc + parseInt(curr.cantidad || 0), 0);
        const bajoStock = inventarioData.filter(i => i.cantidad <= i.stock_minimo).length;

        document.getElementById('cardTotalElementos').textContent = total;
        document.getElementById('cardSubCategorias').textContent = categoriasUnicas === 1 ? 'En 1 categoría' : `En ${categoriasUnicas} categorías`;
        document.getElementById('cardTotalUnidades').textContent = totalUnidades;
        document.getElementById('cardBajoStock').textContent = bajoStock;
    }

    // ==================== MODAL NUEVO / EDITAR ====================
    function abrirModalNuevo() {
        document.getElementById('formElemento').reset();
        document.getElementById('modalItemId').value = '';
        document.getElementById('modalElementoTitle').textContent = 'Nuevo elemento';
        document.getElementById('modalElementoSubtitle').textContent = 'Herramienta, material o insumo de la unidad apícola.';
        document.getElementById('btnSubmitElemento').textContent = 'Guardar elemento';
        document.getElementById('wrapCantidad').style.display = 'block';
        document.getElementById('inputCantidad').required = true;
        document.getElementById('modalElementoBackdrop').classList.add('is-open');
    }

    function abrirModalEditar(id) {
        const item = inventarioData.find(i => i.id === id);
        if (!item) return;

        document.getElementById('modalItemId').value = item.id;
        document.getElementById('modalElementoTitle').textContent = 'Editar elemento';
        document.getElementById('modalElementoSubtitle').textContent = `Modificar información de ${item.codigo}.`;
        document.getElementById('btnSubmitElemento').textContent = 'Guardar cambios';

        document.getElementById('inputNombre').value = item.nombre;
        document.getElementById('inputCodigo').value = item.codigo;
        document.getElementById('inputCategoria').value = item.categoria;
        document.getElementById('inputStockMinimo').value = item.stock_minimo;
        document.getElementById('inputEstado').value = item.estado;
        document.getElementById('inputResponsable').value = item.responsable;

        // Ocultar cantidad en edición para dirigir cambios a través de movimientos de trazabilidad
        document.getElementById('wrapCantidad').style.display = 'none';
        document.getElementById('inputCantidad').required = false;

        document.getElementById('modalElementoBackdrop').classList.add('is-open');
    }

    function cerrarModalElemento() {
        document.getElementById('modalElementoBackdrop').classList.remove('is-open');
    }

    async function guardarElementoForm(e) {
        e.preventDefault();
        const id = document.getElementById('modalItemId').value;
        const isEdit = !!id;

        const payload = {
            nombre: document.getElementById('inputNombre').value.trim(),
            codigo: document.getElementById('inputCodigo').value.trim(),
            categoria: document.getElementById('inputCategoria').value,
            stock_minimo: parseInt(document.getElementById('inputStockMinimo').value) || 0,
            estado: document.getElementById('inputEstado').value,
            responsable: document.getElementById('inputResponsable').value,
        };

        if (!isEdit) {
            payload.cantidad = parseInt(document.getElementById('inputCantidad').value) || 0;
        }

        const url = isEdit ? `{{ url('apicola/inventario') }}/${id}` : `{{ route('apicola.admin.inventario.store') }}`;
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const resp = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await resp.json();

            if (resp.ok && data.success) {
                if (isEdit) {
                    const idx = inventarioData.findIndex(i => i.id == id);
                    if (idx !== -1) inventarioData[idx] = data.data;
                } else {
                    inventarioData.unshift(data.data);
                }

                cerrarModalElemento();
                renderInventarioTable();
                updateMetricsCards();
                showToast(data.message || 'Elemento guardado con éxito');
            } else {
                alert(data.message || 'Ocurrió un error al guardar el elemento. Verifica los datos.');
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión al servidor al guardar el elemento.');
        }
    }

    // ==================== MODAL REGISTRAR MOVIMIENTO ====================
    function abrirModalMovimiento(id) {
        const item = inventarioData.find(i => i.id === id);
        if (!item) return;

        selectedItemId = item.id;
        document.getElementById('formMovimiento').reset();
        document.getElementById('movItemId').value = item.id;
        document.getElementById('movItemNombre').textContent = item.nombre;
        document.getElementById('movItemCodigo').textContent = item.codigo;
        document.getElementById('movItemStockActual').textContent = item.cantidad;
        document.getElementById('movFecha').value = new Date().toISOString().split('T')[0];
        setTipoMovimiento('Entrada');

        document.getElementById('modalMovimientoBackdrop').classList.add('is-open');
    }

    function cerrarModalMovimiento() {
        document.getElementById('modalMovimientoBackdrop').classList.remove('is-open');
    }

    function setTipoMovimiento(tipo) {
        document.getElementById('movTipo').value = tipo;

        const btnEntrada = document.getElementById('btnTipoEntrada');
        const btnSalida = document.getElementById('btnTipoSalida');
        const btnAjuste = document.getElementById('btnTipoAjuste');

        btnEntrada.className = 'type-pill-btn' + (tipo === 'Entrada' ? ' active-entrada' : '');
        btnSalida.className = 'type-pill-btn' + (tipo === 'Salida' ? ' active-salida' : '');
        btnAjuste.className = 'type-pill-btn' + (tipo === 'Ajuste' ? ' active-ajuste' : '');
    }

    async function guardarMovimientoForm(e) {
        e.preventDefault();
        const id = document.getElementById('movItemId').value;
        const payload = {
            tipo: document.getElementById('movTipo').value,
            cantidad: parseInt(document.getElementById('movCantidad').value) || 1,
            fecha: document.getElementById('movFecha').value,
            motivo: document.getElementById('movMotivo').value.trim(),
            responsable: document.getElementById('movResponsable').value,
        };

        try {
            const resp = await fetch(`{{ url('apicola/inventario') }}/${id}/movimiento`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const data = await resp.json();

            if (resp.ok && data.success) {
                const idx = inventarioData.findIndex(i => i.id == id);
                if (idx !== -1) {
                    inventarioData[idx] = data.data.item;
                }

                // Actualizar métrica de movimientos
                const currMovs = parseInt(document.getElementById('cardTotalMovimientos').textContent) || 0;
                document.getElementById('cardTotalMovimientos').textContent = currMovs + 1;

                cerrarModalMovimiento();
                renderInventarioTable();
                updateMetricsCards();
                showToast(`Movimiento de ${payload.tipo} registrado.`);
            } else {
                alert(data.message || 'Error al registrar el movimiento.');
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión al registrar el movimiento.');
        }
    }

    // ==================== MODAL VER DETALLE ====================
    async function abrirModalDetalle(id) {
        selectedItemId = id;
        const item = inventarioData.find(i => i.id === id);
        if (!item) return;

        document.getElementById('detCategoria').textContent = item.categoria.toUpperCase();
        document.getElementById('detNombre').textContent = item.nombre;
        document.getElementById('detCodigo').textContent = item.codigo;
        document.getElementById('detCantidad').textContent = item.cantidad;
        document.getElementById('detStockMinimo').textContent = item.stock_minimo;
        document.getElementById('detResponsable').textContent = item.responsable;

        const isActivo = item.estado === 'Activo';
        document.getElementById('detEstadoBadge').innerHTML = `
            <span class="badge-status-pill ${isActivo ? 'activo' : 'inactivo'}">${item.estado}</span>
        `;
        document.getElementById('btnToggleEstadoDetalle').textContent = isActivo ? 'Desactivar elemento' : 'Reactivar elemento';
        document.getElementById('btnToggleEstadoDetalle').style.color = isActivo ? '#dc2626' : '#059669';

        // Cargar historial de movimientos
        const tbody = document.getElementById('detHistorialBody');
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 1rem;">Cargando historial...</td></tr>';
        document.getElementById('modalDetalleBackdrop').classList.add('is-open');

        try {
            const resp = await fetch(`{{ url('apicola/inventario') }}/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const res = await resp.json();

            if (res.success && res.data.movimientos) {
                tbody.innerHTML = '';
                if (res.data.movimientos.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 1.5rem;">Sin movimientos registrados aún.</td></tr>';
                    return;
                }

                res.data.movimientos.forEach(m => {
                    const tr = document.createElement('tr');
                    const badgeColor = m.tipo === 'Entrada' ? '#059669' : (m.tipo === 'Salida' ? '#dc2626' : '#0284c7');
                    const sign = m.tipo === 'Entrada' ? '+' : (m.tipo === 'Salida' ? '-' : '=');

                    tr.innerHTML = `
                        <td style="font-weight: 600; color: #64748b;">${m.fecha ? m.fecha.substring(0, 10) : '-'}</td>
                        <td>
                            <span style="font-weight: 800; color: ${badgeColor}; font-size: 0.78rem;">
                                ${sign} ${m.tipo}
                            </span>
                        </td>
                        <td style="font-weight: 800; color: #0f172a;">${m.cantidad}</td>
                        <td style="font-weight: 600; color: #64748b;">${m.stock_nuevo}</td>
                        <td>${m.motivo || '-'}</td>
                        <td style="color: #64748b;">${m.responsable || '-'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        } catch (e) {
            console.error(e);
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #ef4444; padding: 1rem;">No se pudo cargar el historial.</td></tr>';
        }
    }

    function cerrarModalDetalle() {
        document.getElementById('modalDetalleBackdrop').classList.remove('is-open');
    }

    function abrirMovimientoDesdeDetalle() {
        const id = selectedItemId;
        cerrarModalDetalle();
        if (id) {
            abrirModalMovimiento(id);
        }
    }

    async function toggleEstadoDesdeDetalle() {
        if (!selectedItemId) return;
        try {
            const resp = await fetch(`{{ url('apicola/inventario') }}/${selectedItemId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });
            const data = await resp.json();
            if (data.success) {
                const idx = inventarioData.findIndex(i => i.id == selectedItemId);
                if (idx !== -1) {
                    inventarioData[idx].estado = data.data.estado;
                }
                cerrarModalDetalle();
                renderInventarioTable();
                showToast(data.message);
            }
        } catch (e) {
            console.error(e);
            alert('Error al alternar estado.');
        }
    }

    // ==================== MODAL CATEGORÍAS ====================
    function abrirModalCategorias() {
        const catsMap = {};
        inventarioData.forEach(item => {
            if (!catsMap[item.categoria]) {
                catsMap[item.categoria] = { items: 0, unidades: 0 };
            }
            catsMap[item.categoria].items += 1;
            catsMap[item.categoria].unidades += parseInt(item.cantidad || 0);
        });

        const container = document.getElementById('categoriasListContainer');
        container.innerHTML = '';

        Object.keys(catsMap).forEach(cat => {
            const row = document.createElement('div');
            row.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;';
            row.innerHTML = `
                <div>
                    <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">${cat}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">${catsMap[cat].items} elementos registrados</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.15rem; font-weight: 900; color: #059669;">${catsMap[cat].unidades}</div>
                    <div style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">UNIDADES</div>
                </div>
            `;
            container.appendChild(row);
        });

        document.getElementById('modalCategoriasBackdrop').classList.add('is-open');
    }

    function cerrarModalCategorias() {
        document.getElementById('modalCategoriasBackdrop').classList.remove('is-open');
    }

    // Cerrar modales con clic en backdrop
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('ap-modal-backdrop')) {
            e.target.classList.remove('is-open');
        }
    });
</script>
@endsection
