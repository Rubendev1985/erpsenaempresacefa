@extends('apicola::Aprendiz.layout')

@section('title', 'Gestión de Colmenas • SENA APÍCOLA')

@section('breadcrumb')
    <span class="active-page">Gestión de colmenas</span>
@endsection

@section('styles')
<style>
    /* Estilos Premium del Módulo de Colmenas del Aprendiz */
    .ap-view-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ap-view-subtitle-tag {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #059669;
        margin-bottom: 0.2rem;
    }

    .ap-view-main-title {
        font-size: 1.85rem;
        font-weight: 900;
        letter-spacing: -0.03em;
        color: #0f172a;
        margin: 0;
    }

    .ap-user-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .ap-user-chip-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        color: #ffffff;
        font-weight: 800;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ap-user-chip-name {
        font-size: 0.86rem;
        font-weight: 700;
        color: #0f172a;
        display: block;
    }

    .ap-user-chip-role {
        font-size: 0.72rem;
        color: #64748b;
        display: block;
    }

    /* Grid de Métricas (Borde lateral exacto a la captura) */
    .ap-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .ap-metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ap-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -3px rgba(15, 23, 42, 0.07);
    }

    .ap-metric-card.border-green { border-left: 4.5px solid #10b981; }
    .ap-metric-card.border-amber { border-left: 4.5px solid #f59e0b; }
    .ap-metric-card.border-red   { border-left: 4.5px solid #ef4444; }

    .ap-metric-card-title {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.45rem;
    }

    .ap-metric-card-num {
        font-size: 2.25rem;
        font-weight: 900;
        letter-spacing: -0.03em;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 0.4rem;
    }

    .ap-metric-card-sub {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    /* Sección de Listado */
    .ap-list-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .ap-list-top-bar {
        margin-bottom: 1.25rem;
    }

    .ap-list-title {
        font-size: 1.25rem;
        font-weight: 900;
        letter-spacing: -0.02em;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .ap-list-desc {
        font-size: 0.84rem;
        color: #64748b;
        margin: 0;
    }

    /* Barra de Controles */
    .ap-controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .ap-controls-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-grow: 1;
        flex-wrap: wrap;
    }

    .ap-search-wrapper {
        position: relative;
        min-width: 260px;
        flex-grow: 1;
    }

    .ap-search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .ap-search-wrapper input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.85rem;
        font-family: inherit;
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
    }

    .ap-search-wrapper input:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    .ap-filter-select {
        padding: 9px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.85rem;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .ap-filter-select:focus {
        border-color: #059669;
    }

    .ap-btn-solid-green {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #047857;
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(4, 120, 87, 0.25);
    }

    .ap-btn-solid-green:hover {
        background: #065f46;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.35);
    }

    /* Tabla */
    .ap-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .ap-table-scroll {
        overflow-x: auto;
    }

    .ap-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.86rem;
    }

    .ap-table thead tr {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .ap-table th {
        padding: 12px 16px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .ap-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s;
    }

    .ap-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .ap-table td {
        padding: 14px 16px;
        color: #334155;
        vertical-align: middle;
    }

    .ap-code-badge {
        font-weight: 800;
        color: #0f172a;
        font-family: inherit;
    }

    /* Badges de Estado */
    .ap-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .ap-status-badge.activa {
        background: #ecfdf5;
        color: #059669;
    }

    .ap-status-badge.en-revision {
        background: #fffbeb;
        color: #d97706;
    }

    .ap-status-badge.inactiva {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Botones de Acción en Tabla */
    .ap-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ap-action-btn {
        background: none;
        border: none;
        padding: 0;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: color 0.15s;
    }

    .ap-action-btn.view { color: #059669; }
    .ap-action-btn.view:hover { color: #047857; text-decoration: underline; }

    .ap-action-btn.edit { color: #475569; }
    .ap-action-btn.edit:hover { color: #0f172a; text-decoration: underline; }

    .ap-action-btn.inspeccion { color: #0284c7; }
    .ap-action-btn.inspeccion:hover { color: #0369a1; text-decoration: underline; }

    .ap-action-btn.deactivate { color: #dc2626; }
    .ap-action-btn.deactivate:hover { color: #b91c1c; text-decoration: underline; }

    .ap-action-btn.activate { color: #059669; }
    .ap-action-btn.activate:hover { color: #047857; text-decoration: underline; }

    .ap-table-footer-row {
        padding: 12px 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
        color: #64748b;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* Modal Backdrop & Window (Exacto a la captura) */
    .ap-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .ap-modal-backdrop.is-open {
        display: flex;
    }

    .ap-modal-window {
        background: #ffffff;
        border-radius: 18px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        animation: modalFadeIn 0.2s ease-out;
        overflow: hidden;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to   { opacity: 1; transform: scale(1); }
    }

    .ap-modal-body {
        padding: 2.2rem 2.2rem 1.75rem;
    }

    .ap-modal-title {
        font-size: 1.6rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 4px 0;
        letter-spacing: -0.02em;
    }

    .ap-modal-sub {
        font-size: 0.88rem;
        color: #64748b;
        margin: 0 0 1.5rem 0;
    }

    .ap-form-field {
        margin-bottom: 1.25rem;
    }

    .ap-field-label {
        display: block;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .ap-field-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.90rem;
        font-family: inherit;
        background: #ffffff;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
    }

    .ap-field-input:focus {
        border-color: #047857;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.12);
    }

    .ap-field-hint {
        color: #94a3b8;
        font-size: 0.76rem;
        margin-top: 5px;
        margin-bottom: 0;
    }

    .ap-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 1.25rem;
    }

    .ap-modal-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 1.75rem;
    }

    .ap-btn-modal-cancel {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
    }

    .ap-btn-modal-cancel:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .ap-btn-modal-save {
        background: #047857;
        color: #ffffff;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        box-shadow: 0 2px 6px rgba(4, 120, 87, 0.25);
    }

    .ap-btn-modal-save:hover {
        background: #065f46;
    }

    /* Toast Notification */
    .ap-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #071e18;
        color: #ffffff;
        border-left: 4px solid #10b981;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
        z-index: 9999;
        display: none;
        animation: toastSlideIn 0.25s ease-out;
    }

    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 992px) {
        .ap-metrics-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
    <!-- Encabezado de la vista con perfil aprendiz -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">COLMENAS</span>
            <h1 class="ap-view-main-title">Gestión de colmenas</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar" style="background: #f59e0b;">
                {{ strtoupper(substr(Auth::user()?->nickname ?? 'AP', 0, 2)) }}
            </div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">{{ Auth::user()?->full_name ?? (Auth::user()?->nickname ?? 'Jhon Ramírez Ortiz') }}</span>
                <span class="ap-user-chip-role">Rol: Aprendiz Apícola</span>
            </div>
        </div>
    </div>

    <!-- 4 Tarjetas de Métricas (Idénticas a la captura) -->
    <div class="ap-metrics-grid">
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">COLMENAS REGISTRADAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricTotal">48</div>
            <div class="ap-metric-card-sub">En 5 apiarios activos</div>
        </div>

        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">ACTIVAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricActivas">42</div>
            <div class="ap-metric-card-sub">En producción</div>
        </div>

        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">EN REVISIÓN</div>
            <div class="ap-metric-card-num" id="colmenaMetricRevision">4</div>
            <div class="ap-metric-card-sub">Cría abierta / seguimiento</div>
        </div>

        <div class="ap-metric-card border-red">
            <div class="ap-metric-card-title">INACTIVAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricInactivas">2</div>
            <div class="ap-metric-card-sub">No admiten visitas</div>
        </div>
    </div>

    <!-- Sección: Listado de Colmenas -->
    <div class="ap-list-section">
        <div class="ap-list-top-bar">
            <h2 class="ap-list-title">Listado de colmenas</h2>
            <p class="ap-list-desc">Toda colmena pertenece a un apiario. Solo se registran visitas a colmenas activas o en revisión.</p>
        </div>

        <!-- Fila de Controles: Búsqueda, Filtros y Botón Nuevo -->
        <div class="ap-controls-bar">
            <div class="ap-controls-left" style="max-width: 680px;">
                <div class="ap-search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="colmenaSearchInput" placeholder="Buscar por código..." oninput="filterColmenas()">
                </div>
                <select id="colmenaApiarioFilter" class="ap-filter-select" onchange="filterColmenas()">
                    <option value="all">Apiario: todos</option>
                    <option value="Apiario La Angostura" selected>Apiario La Angostura</option>
                    <option value="Apiario Los Pinos">Apiario Los Pinos</option>
                    <option value="Apiario El Cedro">Apiario El Cedro</option>
                    <option value="Apiario San José">Apiario San José</option>
                    <option value="Apiario La Esperanza">Apiario La Esperanza</option>
                </select>
                <select id="colmenaEstadoFilter" class="ap-filter-select" onchange="filterColmenas()">
                    <option value="all">Estado: todos</option>
                    <option value="Activa">Activa</option>
                    <option value="En revisión">En revisión</option>
                    <option value="Inactiva">Inactiva</option>
                </select>
            </div>

            <div class="ap-controls-right">
                <button type="button" class="ap-btn-solid-green" onclick="openNewColmenaModal()">
                    <i class="fas fa-plus"></i> Nueva colmena
                </button>
            </div>
        </div>

        <!-- Tabla de Colmenas con columna TIPO DE ABEJA -->
        <div class="ap-table-card">
            <div class="ap-table-scroll">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th>CÓDIGO</th>
                            <th>APIARIO</th>
                            <th>TIPO DE ABEJA</th>
                            <th>RESPONSABLE</th>
                            <th>INSTALACIÓN</th>
                            <th>ÚLTIMA VISITA</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="colmenasTableBody">
                        <!-- Generado dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="ap-table-footer-row">
                <div>
                    Mostrando <strong id="colmenasShownCount">8</strong> de <strong id="colmenasTotalCount">8</strong> colmenas
                </div>
                <div>
                    Las colmenas no se eliminan: se desactivan para preservar la trazabilidad.
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="ap-toast" id="colmenaToast"></div>
@endsection

@section('modals')
    <!-- ==================== MODAL: NUEVA / EDITAR COLMENA (DISEÑO EXACTO A LA CAPTURA) ==================== -->
    <div class="ap-modal-backdrop" id="modalColmenaBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body">
                <h2 class="ap-modal-title" id="modalColmenaTitle">Nueva colmena</h2>
                <p class="ap-modal-sub">Toda colmena pertenece a un apiario activo.</p>

                <form id="formColmena" onsubmit="saveColmenaForm(event)">
                    <input type="hidden" id="modalColmenaId" value="">

                    <!-- Fila 1: CÓDIGO (izq) y FECHA DE INSTALACIÓN (der) -->
                    <div class="ap-grid-2col">
                        <div>
                            <label class="ap-field-label" for="modalColmenaCode">CÓDIGO</label>
                            <input type="text" id="modalColmenaCode" class="ap-field-input" value="COL-049" placeholder="COL-049" required>
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalColmenaFecha">FECHA DE INSTALACIÓN</label>
                            <input type="date" id="modalColmenaFecha" class="ap-field-input" value="2026-08-20" required>
                        </div>
                    </div>

                    <!-- Fila 2: APIARIO (ancho completo) -->
                    <div class="ap-form-field">
                        <label class="ap-field-label" for="modalColmenaApiario">APIARIO</label>
                        <select id="modalColmenaApiario" class="ap-field-input" required>
                            <option value="Apiario La Angostura" selected>Apiario La Angostura</option>
                            <option value="Apiario Los Pinos">Apiario Los Pinos</option>
                            <option value="Apiario El Cedro">Apiario El Cedro</option>
                            <option value="Apiario San José">Apiario San José</option>
                            <option value="Apiario La Esperanza">Apiario La Esperanza</option>
                        </select>
                        <p class="ap-field-hint">Solo se listan apiarios activos.</p>
                    </div>

                    <!-- Fila 3: TIPO DE ABEJA (izq) y ESTADO (der) -->
                    <div class="ap-grid-2col">
                        <div>
                            <label class="ap-field-label" for="modalColmenaTipoAbeja">TIPO DE ABEJA</label>
                            <select id="modalColmenaTipoAbeja" class="ap-field-input" required>
                                <option value="Apis mellifera" selected>Apis mellifera</option>
                                <option value="Melipona eburnea">Melipona eburnea</option>
                                <option value="Tetragonisca angustula">Tetragonisca angustula</option>
                                <option value="Scaptotrigona pectoralis">Scaptotrigona pectoralis</option>
                            </select>
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalColmenaEstado">ESTADO</label>
                            <select id="modalColmenaEstado" class="ap-field-input" required>
                                <option value="Activa" selected>Activa</option>
                                <option value="En revisión">En revisión</option>
                                <option value="Inactiva">Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 4: RESPONSABLE (ancho completo) -->
                    <div class="ap-form-field" style="margin-bottom: 1.5rem;">
                        <label class="ap-field-label" for="modalColmenaResponsable">RESPONSABLE</label>
                        <select id="modalColmenaResponsable" class="ap-field-input" required>
                            <option value="Jhon Ramírez Ortiz" selected>Jhon Ramírez Ortiz</option>
                            <option value="Sergio Barrera">Sergio Barrera</option>
                            <option value="Aprendiz SENA">Aprendiz SENA</option>
                        </select>
                    </div>

                    <!-- Footer: Cancelar y Guardar colmena -->
                    <div class="ap-modal-footer">
                        <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaModal()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" id="btnSaveColmena">Guardar colmena</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: DETALLE DE COLMENA ==================== -->
    <div class="ap-modal-backdrop" id="modalColmenaDetailBackdrop">
        <div class="ap-modal-window" style="max-width: 480px;">
            <div class="ap-modal-body">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" style="font-size:0.68rem;">DETALLE DE COLMENA</span>
                        <h2 class="ap-modal-title" id="colmenaDetailCode" style="margin-bottom:0;">COL-001</h2>
                    </div>
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaDetailModal()" style="padding:6px 12px;">✕</button>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.2rem; margin-bottom:1.2rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">APIARIO</span>
                            <strong id="colmenaDetailApiario" style="font-size:0.88rem; color:#0f172a;">Apiario La Angostura</strong>
                        </div>
                        <div>
                            <span class="ap-field-label">ESTADO SANITARIO</span>
                            <span id="colmenaDetailStatusBadge" class="ap-status-badge activa">Activa</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">TIPO DE ABEJA</span>
                            <span id="colmenaDetailTipoAbeja" style="font-size:0.86rem; color:#334155; font-weight: 600;">Apis mellifera</span>
                        </div>
                        <div>
                            <span class="ap-field-label">RESPONSABLE</span>
                            <span id="colmenaDetailResp" style="font-size:0.86rem; color:#334155;">Jhon Ramírez Ortiz</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <span class="ap-field-label">INSTALACIÓN</span>
                            <span id="colmenaDetailInstalacion" style="font-size:0.86rem; color:#64748b;">12/01/2026</span>
                        </div>
                        <div>
                            <span class="ap-field-label">ÚLTIMA VISITA</span>
                            <span id="colmenaDetailLastVisit" style="font-size:0.86rem; color:#059669; font-weight:700;">15/08/2026</span>
                        </div>
                    </div>
                </div>

                <p id="colmenaDetailNotas" style="font-size:0.84rem; color:#475569; line-height:1.5; margin-bottom:1.5rem;"></p>

                <div class="ap-modal-footer" style="justify-content:space-between;">
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaDetailModal()">Cerrar</button>
                    <button type="button" class="ap-btn-modal-save" id="btnEditFromColmenaDetail">Editar datos</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // ==================== DATOS INICIALES (Exactos a la captura de pantalla: 8 colmenas) ====================
    let colmenasList = [
        {
            id: 1,
            code: "COL-001",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Jhon Ramírez Ortiz",
            instalacion: "12/01/2026",
            ultimaVisita: "15/08/2026",
            estado: "Activa",
            notas: "Colmena vigorosa con excelente postura de reina y reservas óptimas de miel y polen."
        },
        {
            id: 2,
            code: "COL-002",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Sergio Barrera",
            instalacion: "14/01/2026",
            ultimaVisita: "18/08/2026",
            estado: "Activa",
            notas: "Cámara de cría completa. Sin signos de enfermedades ni ácaros."
        },
        {
            id: 3,
            code: "COL-003",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Melipona eburnea",
            responsable: "Jhon Ramírez Ortiz",
            instalacion: "15/01/2026",
            ultimaVisita: "19/08/2026",
            estado: "Activa",
            notas: "Colonia en expansión rápida; se instaló segunda alza melaria."
        },
        {
            id: 4,
            code: "COL-004",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Aprendiz SENA",
            instalacion: "20/01/2026",
            ultimaVisita: "16/08/2026",
            estado: "Activa",
            notas: "Excelente actividad en piquera y recolección activa de néctar."
        },
        {
            id: 14,
            code: "COL-014",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Jhon Ramírez Ortiz",
            instalacion: "05/02/2026",
            ultimaVisita: "17/08/2026",
            estado: "En revisión",
            notas: "Se observó baja postura y celda real en desarrollo. Programada revisión de recambio de reina."
        },
        {
            id: 16,
            code: "COL-016",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Tetragonisca angustula",
            responsable: "Sergio Barrera",
            instalacion: "10/02/2026",
            ultimaVisita: "02/05/2026",
            estado: "Inactiva",
            notas: "Colmena colapsada por enjambrazón previa. Material desinfectado y disponible para nuevo núcleo."
        },
        {
            id: 28,
            code: "COL-028",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Jhon Ramírez Ortiz",
            instalacion: "22/02/2026",
            ultimaVisita: "18/08/2026",
            estado: "En revisión",
            notas: "Población reducida; se aplicó jarabe 1:1 estimulante."
        },
        {
            id: 42,
            code: "COL-042",
            apiario: "Apiario La Angostura",
            tipoAbeja: "Apis mellifera",
            responsable: "Sergio Barrera",
            instalacion: "01/03/2026",
            ultimaVisita: "10/06/2026",
            estado: "Inactiva",
            notas: "Pérdida por pillaje en época de sequía."
        }
    ];

    // Base del censo del apiario (para reflejar 48 colmenas registradas como en la captura)
    let metricsBase = {
        total: 48,
        activas: 42,
        revision: 4,
        inactivas: 2
    };

    document.addEventListener('DOMContentLoaded', () => {
        renderColmenasTable();
        updateColmenasMetrics();
    });

    function showToast(msg) {
        const toast = document.getElementById('colmenaToast');
        if (!toast) return;
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3200);
    }

    function updateColmenasMetrics() {
        const total = metricsBase.total;
        const activas = metricsBase.activas;
        const revision = metricsBase.revision;
        const inactivas = metricsBase.inactivas;

        document.getElementById('colmenaMetricTotal').textContent = total;
        document.getElementById('colmenaMetricActivas').textContent = activas;
        document.getElementById('colmenaMetricRevision').textContent = revision;
        document.getElementById('colmenaMetricInactivas').textContent = inactivas;

        document.getElementById('colmenasTotalCount').textContent = colmenasList.length;
    }

    function renderColmenasTable() {
        const searchVal = (document.getElementById('colmenaSearchInput')?.value || '').toLowerCase().trim();
        const apiarioVal = document.getElementById('colmenaApiarioFilter')?.value || 'all';
        const estadoVal = document.getElementById('colmenaEstadoFilter')?.value || 'all';

        const filtered = colmenasList.filter(item => {
            const matchSearch = item.code.toLowerCase().includes(searchVal) ||
                                (item.tipoAbeja && item.tipoAbeja.toLowerCase().includes(searchVal)) ||
                                item.responsable.toLowerCase().includes(searchVal);
            const matchApiario = (apiarioVal === 'all') || (item.apiario.includes(apiarioVal) || apiarioVal.includes(item.apiario));
            const matchEstado = (estadoVal === 'all') || (item.estado === estadoVal);
            return matchSearch && matchApiario && matchEstado;
        });

        const tbody = document.getElementById('colmenasTableBody');
        tbody.innerHTML = '';

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center; padding: 2.5rem 1rem; color: #94a3b8;">
                        <i class="fas fa-search" style="font-size: 1.5rem; margin-bottom: 0.5rem; display:block;"></i>
                        No se encontraron colmenas con los criterios seleccionados.
                    </td>
                </tr>
            `;
            document.getElementById('colmenasShownCount').textContent = '0';
            return;
        }

        document.getElementById('colmenasShownCount').textContent = filtered.length;

        filtered.forEach(item => {
            let statusClass = 'activa';
            if (item.estado === 'En revisión') statusClass = 'en-revision';
            else if (item.estado === 'Inactiva') statusClass = 'inactiva';

            const isActiva = item.estado !== 'Inactiva';
            const toggleText = isActiva ? 'Desactivar' : 'Activar';
            const toggleClass = isActiva ? 'deactivate' : 'activate';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <span class="ap-code-badge">${item.code}</span>
                </td>
                <td style="color: #334155;">${item.apiario}</td>
                <td style="color: #1e293b; font-weight: 500;">${item.tipoAbeja || 'Apis mellifera'}</td>
                <td style="color: #475569;">${item.responsable}</td>
                <td style="color: #64748b; font-size: 0.82rem;">${item.instalacion}</td>
                <td style="color: #64748b; font-size: 0.82rem;">${item.ultimaVisita}</td>
                <td>
                    <span class="ap-status-badge ${statusClass}">
                        ${item.estado}
                    </span>
                </td>
                <td>
                    <div class="ap-actions-group">
                        <button type="button" class="ap-action-btn view" onclick="openColmenaDetailModal(${item.id})">Ver detalle</button>
                        <button type="button" class="ap-action-btn edit" onclick="openEditColmenaModal(${item.id})">Editar</button>
                        <a href="{{ url('apicola/aprendiz/colmenas') }}/${item.code}/inspecciones" class="ap-action-btn inspeccion" style="text-decoration: none;">Inspección</a>
                        <button type="button" class="ap-action-btn ${toggleClass}" onclick="toggleColmenaStatus(${item.id})">${toggleText}</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function filterColmenas() {
        renderColmenasTable();
    }

    function openNewColmenaModal() {
        document.getElementById('modalColmenaTitle').textContent = 'Nueva colmena';
        document.getElementById('modalColmenaId').value = '';
        
        // Sugerir COL-049 como se ve en la captura de pantalla
        document.getElementById('modalColmenaCode').value = 'COL-049';
        document.getElementById('modalColmenaFecha').value = '2026-08-20';
        document.getElementById('modalColmenaApiario').value = 'Apiario La Angostura';
        document.getElementById('modalColmenaTipoAbeja').value = 'Apis mellifera';
        document.getElementById('modalColmenaEstado').value = 'Activa';
        document.getElementById('modalColmenaResponsable').value = 'Jhon Ramírez Ortiz';

        document.getElementById('modalColmenaBackdrop').classList.add('is-open');
    }

    function openEditColmenaModal(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;

        document.getElementById('modalColmenaTitle').textContent = 'Editar colmena';
        document.getElementById('modalColmenaId').value = item.id;
        document.getElementById('modalColmenaCode').value = item.code;
        document.getElementById('modalColmenaApiario').value = item.apiario.includes('Angostura') ? 'Apiario La Angostura' : item.apiario;
        document.getElementById('modalColmenaTipoAbeja').value = item.tipoAbeja || 'Apis mellifera';
        document.getElementById('modalColmenaEstado').value = item.estado;
        document.getElementById('modalColmenaResponsable').value = item.responsable;

        // Formato para input type date
        if (item.instalacion && item.instalacion.includes('/')) {
            const parts = item.instalacion.split('/');
            const d = parts[0].padStart(2, '0');
            const m = parts[1].padStart(2, '0');
            const y = parts[2];
            document.getElementById('modalColmenaFecha').value = `${y}-${m}-${d}`;
        }

        document.getElementById('modalColmenaBackdrop').classList.add('is-open');
    }

    function closeColmenaModal() {
        document.getElementById('modalColmenaBackdrop').classList.remove('is-open');
    }

    function saveColmenaForm(e) {
        e.preventDefault();

        const id = document.getElementById('modalColmenaId').value;
        const code = document.getElementById('modalColmenaCode').value.trim();
        const rawFecha = document.getElementById('modalColmenaFecha').value;
        const apiario = document.getElementById('modalColmenaApiario').value;
        const tipoAbeja = document.getElementById('modalColmenaTipoAbeja').value;
        const estado = document.getElementById('modalColmenaEstado').value;
        const responsable = document.getElementById('modalColmenaResponsable').value;

        let instalacionFormatted = rawFecha;
        if (rawFecha && rawFecha.includes('-')) {
            const parts = rawFecha.split('-');
            instalacionFormatted = `${parseInt(parts[2], 10)}/${parseInt(parts[1], 10)}/${parts[0]}`;
        }

        if (id) {
            // Actualización
            const item = colmenasList.find(c => c.id === parseInt(id));
            if (item) {
                const prevEstado = item.estado;
                item.code = code;
                item.apiario = apiario;
                item.tipoAbeja = tipoAbeja;
                item.instalacion = instalacionFormatted;
                item.responsable = responsable;
                item.estado = estado;

                if (prevEstado !== estado) {
                    if (prevEstado === 'Activa') metricsBase.activas--;
                    else if (prevEstado === 'En revisión') metricsBase.revision--;
                    else if (prevEstado === 'Inactiva') metricsBase.inactivas--;

                    if (estado === 'Activa') metricsBase.activas++;
                    else if (estado === 'En revisión') metricsBase.revision++;
                    else if (estado === 'Inactiva') metricsBase.inactivas++;
                }

                showToast(`Colmena "${code}" actualizada correctamente.`);
            }
        } else {
            // Nueva Colmena
            const exists = colmenasList.some(c => c.code.toLowerCase() === code.toLowerCase());
            if (exists) {
                showToast(`Aviso: El código "${code}" ya se encuentra registrado.`);
                return;
            }

            const newId = colmenasList.length ? Math.max(...colmenasList.map(c => c.id)) + 1 : 1;
            const today = new Date();
            const todayFormatted = `${today.getDate()}/${today.getMonth() + 1}/${today.getFullYear()}`;

            const newColmena = {
                id: newId,
                code: code,
                apiario: apiario,
                tipoAbeja: tipoAbeja,
                responsable: responsable,
                instalacion: instalacionFormatted,
                ultimaVisita: todayFormatted,
                estado: estado,
                notas: "Colmena registrada con éxito en el Centro La Angostura."
            };

            colmenasList.unshift(newColmena);

            // Actualizar métricas globales
            metricsBase.total++;
            if (estado === 'Activa') metricsBase.activas++;
            else if (estado === 'En revisión') metricsBase.revision++;
            else if (estado === 'Inactiva') metricsBase.inactivas++;

            showToast(`Colmena "${code}" guardada con éxito.`);

            // Enviar petición asíncrona al backend para persistencia en BD
            fetch("{{ route('apicola.aprendiz.colmenas.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    apiario_name: apiario,
                    tipo_abeja: tipoAbeja,
                    responsable: responsable,
                    installation_date: rawFecha,
                    last_visit_date: rawFecha,
                    status: estado,
                    notes: newColmena.notas
                })
            }).catch(err => console.log('Registro local exitoso'));
        }

        closeColmenaModal();
        renderColmenasTable();
        updateColmenasMetrics();
    }

    function openColmenaDetailModal(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;

        document.getElementById('colmenaDetailCode').textContent = item.code;
        document.getElementById('colmenaDetailApiario').textContent = item.apiario;
        document.getElementById('colmenaDetailTipoAbeja').textContent = item.tipoAbeja || 'Apis mellifera';
        document.getElementById('colmenaDetailResp').textContent = item.responsable;
        document.getElementById('colmenaDetailInstalacion').textContent = item.instalacion;
        document.getElementById('colmenaDetailLastVisit').textContent = item.ultimaVisita || 'Reciente';
        document.getElementById('colmenaDetailNotas').textContent = item.notas || 'Sin novedades clínicas registradas.';

        const badge = document.getElementById('colmenaDetailStatusBadge');
        badge.textContent = item.estado;
        badge.className = 'ap-status-badge ' + (item.estado === 'Activa' ? 'activa' : (item.estado === 'En revisión' ? 'en-revision' : 'inactiva'));

        document.getElementById('btnEditFromColmenaDetail').onclick = () => {
            closeColmenaDetailModal();
            openEditColmenaModal(item.id);
        };

        document.getElementById('modalColmenaDetailBackdrop').classList.add('is-open');
    }

    function closeColmenaDetailModal() {
        document.getElementById('modalColmenaDetailBackdrop').classList.remove('is-open');
    }

    function openInspeccionModal(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;
        showToast(`Ficha de inspección formativa para "${item.code}" abierta.`);
        openColmenaDetailModal(id);
    }

    function toggleColmenaStatus(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;

        if (item.estado === 'Inactiva') {
            item.estado = 'Activa';
            metricsBase.inactivas--;
            metricsBase.activas++;
            showToast(`Colmena "${item.code}" reactivada en producción.`);
        } else {
            if (item.estado === 'Activa') metricsBase.activas--;
            else if (item.estado === 'En revisión') metricsBase.revision--;
            item.estado = 'Inactiva';
            metricsBase.inactivas++;
            showToast(`Colmena "${item.code}" marcada como inactiva.`);
        }

        renderColmenasTable();
        updateColmenasMetrics();
    }
</script>
@endsection
