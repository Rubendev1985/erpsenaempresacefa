@extends('apicola::Aprendiz.layout')

@section('title', 'Registro e Inspección • ' . ($selectedColmena ?? 'COL-013') . ' • SENA APÍCOLA')

@section('breadcrumb')
    <a href="{{ route('apicola.aprendiz.colmenas.index') }}">Colmenas</a>
    <span>/</span>
    <span style="font-weight: 700; color: #059669;" id="currentColmenaCode">{{ $selectedColmena ?? 'COL-013' }}</span>
    <span>/</span>
    <span class="active-page">Registro e inspección</span>
@endsection

@section('styles')
<style>
    /* Estilos Premium del Submódulo de Registro e Inspección */
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
        font-size: 0.72rem;
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

    /* Grid de Métricas (3 columnas con bordes laterales de colores) */
    .ap-metrics-grid.cols-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    /* Sección de Historial */
    .ap-list-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
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

    /* Barra de Controles y Filtros */
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
        flex-wrap: wrap;
    }

    .ap-date-input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        font-family: inherit;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        width: 130px;
        text-align: center;
        transition: border-color 0.2s;
    }

    .ap-date-input:focus {
        border-color: #059669;
    }

    .ap-btn-outline-green {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        color: #047857;
        border: 1.5px solid #059669;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ap-btn-outline-green:hover {
        background: #ecfdf5;
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

    .ap-filter-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.86rem;
        font-family: inherit;
        background: #ffffff;
        color: #334155;
        outline: none;
        cursor: pointer;
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

    /* Badges de Estado y Alertas */
    .ap-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .ap-status-badge.bueno {
        background: #ecfdf5;
        color: #059669;
    }

    .ap-status-badge.regular {
        background: #fffbeb;
        color: #d97706;
    }

    .ap-status-badge.alerta {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .ap-actions-group {
        display: flex;
        align-items: center;
        gap: 12px;
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

    .ap-table-footer-row {
        padding: 12px 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        color: #64748b;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* Modal Backdrop & Window */
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
        overflow-y: auto;
    }

    .ap-modal-backdrop.is-open {
        display: flex;
    }

    .ap-modal-window {
        background: #ffffff;
        border-radius: 18px;
        width: 100%;
        max-width: 540px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        animation: modalFadeIn 0.2s ease-out;
        overflow: hidden;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to   { opacity: 1; transform: scale(1); }
    }

    .ap-modal-body {
        padding: 2.2rem 2.2rem 1.6rem;
        overflow-y: auto;
    }

    .ap-modal-title {
        font-size: 1.65rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 1.25rem 0;
        letter-spacing: -0.02em;
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

    .ap-coords-row.cols-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }

    .ap-form-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #047857;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Píldoras de Estado Bueno / Regular / Malo */
    .ap-status-pill-group {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }

    .ap-status-pill-btn {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
        text-align: center;
    }

    .ap-status-pill-btn:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    .ap-status-pill-btn.selected-bueno {
        background: #ecfdf5;
        border-color: #10b981;
        color: #047857;
    }

    .ap-status-pill-btn.selected-regular {
        background: #fffbeb;
        border-color: #f59e0b;
        color: #b45309;
    }

    .ap-status-pill-btn.selected-malo {
        background: #fee2e2;
        border-color: #ef4444;
        color: #b91c1c;
    }

    /* Checkbox Cards para Reina, Enfermedades y Labores */
    .ap-checkbox-card-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .ap-checkbox-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        user-select: none;
    }

    .ap-checkbox-card:hover {
        border-color: #94a3b8;
    }

    .ap-checkbox-card.is-checked {
        background: #ecfdf5;
        border-color: #10b981;
        color: #047857;
        font-weight: 700;
    }

    .ap-checkbox-card input[type="checkbox"] {
        accent-color: #059669;
        width: 17px;
        height: 17px;
        cursor: pointer;
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

    /* Toast */
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
        .ap-metrics-grid.cols-3 { grid-template-columns: 1fr; }
        .ap-coords-row.cols-3 { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
    <!-- Encabezado de la vista con perfil aprendiz -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">
                COLMENAS / <span id="colmenaTag">{{ $selectedColmena ?? 'COL-013' }}</span> / REGISTRO E INSPECCIÓN
            </span>
            <h1 class="ap-view-main-title">Registro e inspección</h1>
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

    <!-- 3 Tarjetas de Métricas (Idénticas a la captura de pantalla) -->
    <div class="ap-metrics-grid cols-3">
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">VISITAS REGISTRADAS</div>
            <div class="ap-metric-card-num" id="metricTotalVisitas">4</div>
            <div class="ap-metric-card-sub" id="metricColmenaSub">Colmena {{ $selectedColmena ?? 'COL-013' }}</div>
        </div>

        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">MIEL ACUMULADA</div>
            <div class="ap-metric-card-num" id="metricMielTotal">22,4</div>
            <div class="ap-metric-card-sub">Kilogramos en el período</div>
        </div>

        <div class="ap-metric-card border-red">
            <div class="ap-metric-card-title">ALERTAS SANITARIAS</div>
            <div class="ap-metric-card-num" id="metricAlertasTotal">1</div>
            <div class="ap-metric-card-sub">Enfermedades detectadas</div>
        </div>
    </div>

    <!-- Sección: Historial de la Colmena -->
    <div class="ap-list-section">
        <div class="ap-list-top-bar" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1rem;">
            <div>
                <h2 class="ap-list-title" id="historyColmenaTitle">Historial de la colmena {{ $selectedColmena ?? 'COL-013' }}</h2>
                <p class="ap-list-desc">Una fila por cada visita realizada.</p>
            </div>

            <!-- Selector de colmena rápido -->
            <div style="display:flex; align-items:center; gap:8px;">
                <label class="ap-field-label" style="margin-bottom:0;" for="selectChangeColmena">CAMBIAR COLMENA:</label>
                <select id="selectChangeColmena" class="ap-filter-select" onchange="changeActiveColmena(this.value)">
                    <option value="COL-013" {{ ($selectedColmena ?? 'COL-013') === 'COL-013' ? 'selected' : '' }}>COL-013 (Apiario SENA La Angostura)</option>
                    <option value="COL-001" {{ ($selectedColmena ?? '') === 'COL-001' ? 'selected' : '' }}>COL-001 (Apiario SENA La Angostura)</option>
                    <option value="COL-002" {{ ($selectedColmena ?? '') === 'COL-002' ? 'selected' : '' }}>COL-002 (Apiario SENA La Angostura)</option>
                    <option value="COL-003" {{ ($selectedColmena ?? '') === 'COL-003' ? 'selected' : '' }}>COL-003 (Apiario SENA La Angostura)</option>
                    <option value="COL-011" {{ ($selectedColmena ?? '') === 'COL-011' ? 'selected' : '' }}>COL-011 (Apiario SENA La Angostura)</option>
                    <option value="COL-012" {{ ($selectedColmena ?? '') === 'COL-012' ? 'selected' : '' }}>COL-012 (Apiario SENA La Angostura)</option>
                    <option value="COL-014" {{ ($selectedColmena ?? '') === 'COL-014' ? 'selected' : '' }}>COL-014 (Apiario SENA La Angostura)</option>
                    <option value="COL-015" {{ ($selectedColmena ?? '') === 'COL-015' ? 'selected' : '' }}>COL-015 (Apiario SENA La Angostura)</option>
                    <option value="COL-016" {{ ($selectedColmena ?? '') === 'COL-016' ? 'selected' : '' }}>COL-016 (Apiario SENA La Angostura)</option>
                    <option value="COL-049" {{ ($selectedColmena ?? '') === 'COL-049' ? 'selected' : '' }}>COL-049 (Apiario SENA La Angostura)</option>
                </select>
            </div>
        </div>

        <!-- Fila de Controles: Filtro de fechas y Botón + Registrar visita -->
        <div class="ap-controls-bar" style="margin-top: 1rem;">
            <div class="ap-controls-left">
                <input type="text" id="filterFechaInicio" class="ap-date-input" value="01/03/2026" placeholder="DD/MM/AAAA">
                <input type="text" id="filterFechaFin" class="ap-date-input" value="31/05/2026" placeholder="DD/MM/AAAA">
                <button type="button" class="ap-btn-outline-green" onclick="filterVisitas()">
                    Filtrar
                </button>
            </div>

            <div class="ap-controls-right">
                <button type="button" class="ap-btn-solid-green" onclick="openNewVisitaModal()">
                    <i class="fas fa-plus"></i> Registrar visita
                </button>
            </div>
        </div>

        <!-- Tabla de Visitas e Inspecciones -->
        <div class="ap-table-card">
            <div class="ap-table-scroll">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>N.° REGISTRO</th>
                            <th>ESTADO</th>
                            <th>PRESENCIA DE MIEL</th>
                            <th>MIEL (KG)</th>
                            <th>ENFERMEDADES</th>
                            <th>RESPONSABLE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="visitasTableBody">
                        <!-- Renderizado dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="ap-table-footer-row">
                <div id="visitasSummaryText">
                    4 visitas entre el 01/03/2026 y el 31/05/2026
                </div>
                <div id="mielHarvestSummaryText" style="font-weight: 700; color: #0f172a;">
                    Total cosechado en el período: 22,4 kg
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="ap-toast" id="inspeccionToast"></div>
@endsection

@section('modals')
    <!-- ==================== MODAL: REGISTRAR / EDITAR VISITA (EXACTO A LAS CAPTURAS) ==================== -->
    <div class="ap-modal-backdrop" id="modalVisitaBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body">
                <h2 class="ap-modal-title" id="modalVisitaTitle">Registrar visita</h2>

                <form id="formVisita" onsubmit="saveVisitaForm(event)">
                    <input type="hidden" id="modalVisitaId" value="">

                    <!-- Fila 1: FECHA, COLMENA, RESPONSABLE -->
                    <div class="ap-coords-row cols-3" style="margin-bottom: 1.25rem;">
                        <div>
                            <label class="ap-field-label" for="modalVisitaFecha">FECHA</label>
                            <input type="text" id="modalVisitaFecha" class="ap-field-input" value="17/09/2026" required>
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalVisitaColmena">COLMENA</label>
                            <input type="text" id="modalVisitaColmena" class="ap-field-input" value="{{ $selectedColmena ?? 'COL-013' }}" required>
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalVisitaResponsable">RESPONSABLE</label>
                            <input type="text" id="modalVisitaResponsable" class="ap-field-input" value="Sergio Barrera" required>
                        </div>
                    </div>

                    <!-- Sección: Estado de la Colmena -->
                    <div class="ap-form-section-title">ESTADO DE LA COLMENA</div>
                    
                    <div class="ap-form-field">
                        <label class="ap-field-label">ESTADO GENERAL</label>
                        <div class="ap-status-pill-group">
                            <button type="button" class="ap-status-pill-btn selected-bueno" id="btnEstGenBueno" onclick="setEstadoGeneral('Bueno')">Bueno</button>
                            <button type="button" class="ap-status-pill-btn" id="btnEstGenRegular" onclick="setEstadoGeneral('Regular')">Regular</button>
                            <button type="button" class="ap-status-pill-btn" id="btnEstGenMalo" onclick="setEstadoGeneral('Malo')">Malo</button>
                        </div>
                        <input type="hidden" id="modalVisitaEstadoGeneral" value="Bueno">
                    </div>

                    <div class="ap-form-field">
                        <label class="ap-field-label">PRESENCIA DE MIEL</label>
                        <div class="ap-status-pill-group">
                            <button type="button" class="ap-status-pill-btn selected-bueno" id="btnPresMielBueno" onclick="setPresenciaMiel('Bueno')">Bueno</button>
                            <button type="button" class="ap-status-pill-btn" id="btnPresMielRegular" onclick="setPresenciaMiel('Regular')">Regular</button>
                            <button type="button" class="ap-status-pill-btn" id="btnPresMielMalo" onclick="setPresenciaMiel('Malo')">Malo</button>
                        </div>
                        <input type="hidden" id="modalVisitaPresenciaMiel" value="Bueno">
                    </div>

                    <div class="ap-form-field">
                        <label class="ap-field-label" for="modalVisitaMielKg">MIEL COSECHADA (KG)</label>
                        <input type="number" step="0.1" min="0" id="modalVisitaMielKg" class="ap-field-input" value="0" required>
                        <p class="ap-field-hint">Deje 0 si en esta visita no se cosechó miel.</p>
                    </div>

                    <!-- Sección: Reina -->
                    <div class="ap-form-section-title">REINA</div>

                    <div class="ap-checkbox-card-group" style="margin-bottom: 1rem;">
                        <label class="ap-checkbox-card is-checked" id="lblSeVioReina">
                            <input type="checkbox" id="chkSeVioReina" checked onchange="toggleCheckboxCard(this, 'lblSeVioReina')">
                            <span>Se vio la reina</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEstaMarcada">
                            <input type="checkbox" id="chkEstaMarcada" onchange="toggleCheckboxCard(this, 'lblEstaMarcada')">
                            <span>Está marcada</span>
                        </label>
                    </div>

                    <div class="ap-form-field">
                        <label class="ap-field-label" for="modalVisitaColorMarca">COLOR DE LA MARCA</label>
                        <select id="modalVisitaColorMarca" class="ap-field-input">
                            <option value="Sin marcar" selected>Sin marcar</option>
                            <option value="Blanco">Blanco (Años terminados en 1 o 6)</option>
                            <option value="Amarillo">Amarillo (Años terminados en 2 o 7)</option>
                            <option value="Rojo">Rojo (Años terminados en 3 o 8)</option>
                            <option value="Verde">Verde (Años terminados en 4 o 9)</option>
                            <option value="Azul">Azul (Años terminados en 5 o 0)</option>
                        </select>
                    </div>

                    <!-- Sección: Enfermedades Detectadas -->
                    <div class="ap-form-section-title">ENFERMEDADES DETECTADAS</div>
                    <div class="ap-checkbox-card-group" style="margin-bottom: 0.5rem;">
                        <label class="ap-checkbox-card" id="lblEnfVarroa">
                            <input type="checkbox" name="enfermedades" value="Varroa" onchange="toggleCheckboxCard(this, 'lblEnfVarroa')">
                            <span>Varroa</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEnfLoqueAm">
                            <input type="checkbox" name="enfermedades" value="Loque americana" onchange="toggleCheckboxCard(this, 'lblEnfLoqueAm')">
                            <span>Loque americana</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEnfLoqueEu">
                            <input type="checkbox" name="enfermedades" value="Loque europea" onchange="toggleCheckboxCard(this, 'lblEnfLoqueEu')">
                            <span>Loque europea</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEnfAbejasNegras">
                            <input type="checkbox" name="enfermedades" value="Abejas negras" onchange="toggleCheckboxCard(this, 'lblEnfAbejasNegras')">
                            <span>Abejas negras</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEnfEnvenenadas">
                            <input type="checkbox" name="enfermedades" value="Envenenadas" onchange="toggleCheckboxCard(this, 'lblEnfEnvenenadas')">
                            <span>Envenenadas</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblEnfHormiga">
                            <input type="checkbox" name="enfermedades" value="Hormiga" onchange="toggleCheckboxCard(this, 'lblEnfHormiga')">
                            <span>Hormiga</span>
                        </label>
                    </div>
                    <p class="ap-field-hint" style="margin-bottom: 1.25rem;">Marque solo si detectó algo. Puede dejarlo vacío.</p>

                    <!-- Sección: Labores Realizadas -->
                    <div class="ap-form-section-title">LABORES REALIZADAS</div>
                    <div class="ap-checkbox-card-group" style="margin-bottom: 1rem;">
                        <label class="ap-checkbox-card is-checked" id="lblLabCera">
                            <input type="checkbox" name="labores" value="Aplicación de cera" checked onchange="toggleCheckboxCard(this, 'lblLabCera')">
                            <span>Aplicación de cera</span>
                        </label>
                        <label class="ap-checkbox-card is-checked" id="lblLabAlimentacion">
                            <input type="checkbox" name="labores" value="Alimentación" checked onchange="toggleCheckboxCard(this, 'lblLabAlimentacion')">
                            <span>Alimentación</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblLabCuadros">
                            <input type="checkbox" name="labores" value="Cambio de cuadros" onchange="toggleCheckboxCard(this, 'lblLabCuadros')">
                            <span>Cambio de cuadros</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblLabTratamiento">
                            <input type="checkbox" name="labores" value="Tratamiento sanitario" onchange="toggleCheckboxCard(this, 'lblLabTratamiento')">
                            <span>Tratamiento sanitario</span>
                        </label>
                        <label class="ap-checkbox-card" id="lblLabLimpieza">
                            <input type="checkbox" name="labores" value="Limpieza" onchange="toggleCheckboxCard(this, 'lblLabLimpieza')">
                            <span>Limpieza</span>
                        </label>
                    </div>

                    <div class="ap-form-field">
                        <label class="ap-field-label" for="modalVisitaAlimentacion">ALIMENTACIÓN SUMINISTRADA</label>
                        <input type="text" id="modalVisitaAlimentacion" class="ap-field-input" value="Jarabe" placeholder="Jarabe, torta proteica, etc.">
                    </div>

                    <!-- Sección: Observaciones -->
                    <div class="ap-form-field" style="margin-top: 1.2rem;">
                        <label class="ap-field-label" for="modalVisitaObservaciones">OBSERVACIONES</label>
                        <textarea id="modalVisitaObservaciones" class="ap-field-input" rows="3" placeholder="Detalles de la inspección...">Una caja en buen estado, piquera despejada.</textarea>
                    </div>

                    <!-- Footer: Cancelar y Guardar visita -->
                    <div class="ap-modal-footer">
                        <button type="button" class="ap-btn-modal-cancel" onclick="closeVisitaModal()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" id="btnSaveVisita">Guardar visita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: DETALLE DE VISITA ==================== -->
    <div class="ap-modal-backdrop" id="modalVisitaDetailBackdrop">
        <div class="ap-modal-window" style="max-width: 580px;">
            <div class="ap-modal-body">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" id="detailVisitaRegNum">REG-0142</span>
                        <h2 class="ap-modal-title" id="detailVisitaColmena" style="margin-bottom:0;">Inspección Colmena</h2>
                    </div>
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeVisitaDetailModal()" style="padding:6px 12px;">✕</button>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.2rem; margin-bottom:1.2rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">FECHA</span>
                            <strong id="detailVisitaFecha" style="font-size:0.9rem; color:#0f172a;">-</strong>
                        </div>
                        <div>
                            <span class="ap-field-label">ESTADO GENERAL</span>
                            <span id="detailVisitaEstadoGen" class="ap-status-badge bueno">Bueno</span>
                        </div>
                        <div>
                            <span class="ap-field-label">PRESENCIA MIEL</span>
                            <span id="detailVisitaPresMiel" class="ap-status-badge bueno">Bueno</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">MIEL COSECHADA</span>
                            <strong id="detailVisitaMielKg" style="font-size:1.1rem; color:#059669;">0 kg</strong>
                        </div>
                        <div>
                            <span class="ap-field-label">RESPONSABLE</span>
                            <span id="detailVisitaResp" style="font-size:0.85rem; color:#334155; font-weight:600;">-</span>
                        </div>
                        <div>
                            <span class="ap-field-label">ENFERMEDADES</span>
                            <span id="detailVisitaEnfermedades" class="ap-status-badge bueno">Ninguna</span>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #e2e8f0; padding-top: 0.75rem; margin-top: 0.75rem;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                            <div>
                                <span class="ap-field-label">REINA</span>
                                <span id="detailVisitaReinaInfo" style="font-size:0.85rem; color:#334155;">-</span>
                            </div>
                            <div>
                                <span class="ap-field-label">LABORES REALIZADAS</span>
                                <span id="detailVisitaLabores" style="font-size:0.85rem; color:#334155;">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <span class="ap-field-label">OBSERVACIONES</span>
                    <p id="detailVisitaNotas" style="font-size:0.85rem; color:#475569; line-height:1.5; background: #f1f5f9; padding: 10px 14px; border-radius: 8px; margin:0;"></p>
                </div>

                <div class="ap-modal-footer" style="justify-content:space-between;">
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeVisitaDetailModal()">Cerrar</button>
                    <button type="button" class="ap-btn-modal-save" id="btnEditFromVisitaDetail">Editar visita</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // ==================== ESTADO Y DATOS DE INSPECCIÓN ====================
    let activeColmena = "{{ $selectedColmena ?? 'COL-013' }}";

    // Dataset idéntico a las capturas de pantalla proporcionadas
    let inspeccionesList = [
        {
            id: 142,
            regNumber: "REG-0142",
            colmena: "COL-013",
            fecha: "27/05/2026",
            rawFecha: "2026-05-27",
            estadoGeneral: "Bueno",
            presenciaMiel: "Bueno",
            mielKg: 8.5,
            enfermedades: [],
            responsable: "Sergio Barrera",
            reinaVista: true,
            reinaMarcada: false,
            colorMarca: "Sin marcar",
            labores: ["Aplicación de cera", "Alimentación"],
            alimenSuministrada: "Jarabe",
            notas: "Una caja en buen estado, piquera despejada y buen almacenamiento de miel."
        },
        {
            id: 128,
            regNumber: "REG-0128",
            colmena: "COL-013",
            fecha: "10/05/2026",
            rawFecha: "2026-05-10",
            estadoGeneral: "Bueno",
            presenciaMiel: "Regular",
            mielKg: 0.0,
            enfermedades: [],
            responsable: "Sergio Barrera",
            reinaVista: true,
            reinaMarcada: false,
            colorMarca: "Sin marcar",
            labores: ["Alimentación"],
            alimenSuministrada: "Jarabe de estimulación",
            notas: "Monitoreo rutinario; no se realizó cosecha en esta visita."
        },
        {
            id: 106,
            regNumber: "REG-0106",
            colmena: "COL-013",
            fecha: "22/04/2026",
            rawFecha: "2026-04-22",
            estadoGeneral: "Regular",
            presenciaMiel: "Regular",
            mielKg: 6.4,
            enfermedades: ["Varroa"],
            responsable: "Sergio Barrera",
            reinaVista: false,
            reinaMarcada: false,
            colorMarca: "Sin marcar",
            labores: ["Tratamiento sanitario", "Limpieza"],
            alimenSuministrada: "Ninguna",
            notas: "Presencia de ácaros Varroa en nivel moderado. Se aplicó tratamiento con ácido oxálico."
        },
        {
            id: 91,
            regNumber: "REG-0091",
            colmena: "COL-013",
            fecha: "03/04/2026",
            rawFecha: "2026-04-03",
            estadoGeneral: "Bueno",
            presenciaMiel: "Bueno",
            mielKg: 7.5,
            enfermedades: [],
            responsable: "Sergio Barrera",
            reinaVista: true,
            reinaMarcada: false,
            colorMarca: "Sin marcar",
            labores: ["Aplicación de cera"],
            alimenSuministrada: "Ninguna",
            notas: "Inicio de temporada floral favorable con alta actividad pecoreadora."
        }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        renderVisitasTable();
        updateInspeccionMetrics();
    });

    function showToast(msg) {
        const toast = document.getElementById('inspeccionToast');
        if (!toast) return;
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3200);
    }

    function changeActiveColmena(newCode) {
        activeColmena = newCode;
        const curEl = document.getElementById('currentColmenaCode');
        if (curEl) curEl.textContent = newCode;
        const colmTag = document.getElementById('colmenaTag');
        if (colmTag) colmTag.textContent = newCode;
        const subEl = document.getElementById('metricColmenaSub');
        if (subEl) subEl.textContent = `Colmena ${newCode}`;
        const titleEl = document.getElementById('historyColmenaTitle');
        if (titleEl) titleEl.textContent = `Historial de la colmena ${newCode}`;
        const modalColm = document.getElementById('modalVisitaColmena');
        if (modalColm) modalColm.value = newCode;
        
        renderVisitasTable();
        updateInspeccionMetrics();
    }

    // Actualizar 3 tarjetas superiores
    function updateInspeccionMetrics() {
        const filtered = getFilteredVisitas();
        const totalVisitas = filtered.length;
        const mielAcumulada = filtered.reduce((sum, v) => sum + (parseFloat(v.mielKg) || 0), 0);
        const alertasSanitarias = filtered.filter(v => v.enfermedades && v.enfermedades.length > 0).length;

        document.getElementById('metricTotalVisitas').textContent = totalVisitas;
        document.getElementById('metricMielTotal').textContent = mielAcumulada.toFixed(1).replace('.', ',');
        document.getElementById('metricAlertasTotal').textContent = alertasSanitarias;

        document.getElementById('visitasSummaryText').textContent = `${totalVisitas} visitas entre el ${document.getElementById('filterFechaInicio').value} y el ${document.getElementById('filterFechaFin').value}`;
        document.getElementById('mielHarvestSummaryText').textContent = `Total cosechado en el período: ${mielAcumulada.toFixed(1).replace('.', ',')} kg`;
    }

    function parseDateDMY(dmyStr) {
        if (!dmyStr) return null;
        const parts = dmyStr.split('/');
        if (parts.length === 3) {
            return new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
        }
        return null;
    }

    function getFilteredVisitas() {
        const fInicio = parseDateDMY(document.getElementById('filterFechaInicio')?.value);
        const fFin = parseDateDMY(document.getElementById('filterFechaFin')?.value);

        return inspeccionesList.filter(item => {
            const vDate = parseDateDMY(item.fecha);
            if (!vDate) return true;
            if (fInicio && vDate < fInicio) return false;
            if (fFin && vDate > fFin) return false;
            return true;
        });
    }

    function filterVisitas() {
        renderVisitasTable();
        updateInspeccionMetrics();
    }

    function renderVisitasTable() {
        const tbody = document.getElementById('visitasTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        const items = getFilteredVisitas();

        if (items.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center; padding: 2.5rem 1rem; color: #94a3b8;">
                        <i class="fas fa-search" style="font-size: 1.5rem; margin-bottom: 0.5rem; display:block;"></i>
                        No se encontraron visitas registradas en el rango de fechas seleccionado.
                    </td>
                </tr>
            `;
            return;
        }

        items.forEach(v => {
            const tr = document.createElement('tr');

            // Enfermedad badge
            let enfermedadesHtml = '<span style="color: #64748b;">Ninguna</span>';
            if (v.enfermedades && v.enfermedades.length > 0) {
                enfermedadesHtml = v.enfermedades.map(enf => `<span class="ap-status-badge alerta" style="font-size:0.75rem; padding: 2px 10px;">${enf}</span>`).join(' ');
            }

            tr.innerHTML = `
                <td style="font-weight: 700; color: #0f172a;">${v.fecha}</td>
                <td style="color: #475569; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.85rem;">${v.regNumber}</td>
                <td style="color: #334155;">${v.estadoGeneral}</td>
                <td style="color: #334155;">${v.presenciaMiel}</td>
                <td style="color: #0f172a; font-weight: 700;">${parseFloat(v.mielKg).toFixed(1)}</td>
                <td>${enfermedadesHtml}</td>
                <td style="color: #334155;">${v.responsable}</td>
                <td>
                    <div class="ap-actions-group">
                        <button type="button" class="ap-action-btn view" onclick="openVisitaDetailModal(${v.id})">Ver</button>
                        <button type="button" class="ap-action-btn edit" onclick="openEditVisitaModal(${v.id})">Editar</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // ==================== MODAL DE NUEVA / EDITAR VISITA ====================
    function setEstadoGeneral(val) {
        document.getElementById('modalVisitaEstadoGeneral').value = val;
        document.getElementById('btnEstGenBueno').className = 'ap-status-pill-btn' + (val === 'Bueno' ? ' selected-bueno' : '');
        document.getElementById('btnEstGenRegular').className = 'ap-status-pill-btn' + (val === 'Regular' ? ' selected-regular' : '');
        document.getElementById('btnEstGenMalo').className = 'ap-status-pill-btn' + (val === 'Malo' ? ' selected-malo' : '');
    }

    function setPresenciaMiel(val) {
        document.getElementById('modalVisitaPresenciaMiel').value = val;
        document.getElementById('btnPresMielBueno').className = 'ap-status-pill-btn' + (val === 'Bueno' ? ' selected-bueno' : '');
        document.getElementById('btnPresMielRegular').className = 'ap-status-pill-btn' + (val === 'Regular' ? ' selected-regular' : '');
        document.getElementById('btnPresMielMalo').className = 'ap-status-pill-btn' + (val === 'Malo' ? ' selected-malo' : '');
    }

    function toggleCheckboxCard(checkbox, labelId) {
        const lbl = document.getElementById(labelId);
        if (!lbl) return;
        if (checkbox.checked) {
            lbl.classList.add('is-checked');
        } else {
            lbl.classList.remove('is-checked');
        }
    }

    function openNewVisitaModal() {
        document.getElementById('modalVisitaTitle').textContent = 'Registrar visita';
        document.getElementById('modalVisitaId').value = '';
        
        // Formato DD/MM/YYYY para la fecha actual
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        document.getElementById('modalVisitaFecha').value = `${day}/${month}/${year}`;

        document.getElementById('modalVisitaColmena').value = activeColmena;
        document.getElementById('modalVisitaResponsable').value = 'Jhon Ramírez Ortiz';
        document.getElementById('modalVisitaMielKg').value = '0';
        document.getElementById('modalVisitaAlimentacion').value = 'Jarabe';
        document.getElementById('modalVisitaObservaciones').value = 'Una caja en buen estado, piquera despejada.';

        setEstadoGeneral('Bueno');
        setPresenciaMiel('Bueno');

        // Reina
        const chkReina = document.getElementById('chkSeVioReina');
        chkReina.checked = true;
        toggleCheckboxCard(chkReina, 'lblSeVioReina');

        const chkMarcada = document.getElementById('chkEstaMarcada');
        chkMarcada.checked = false;
        toggleCheckboxCard(chkMarcada, 'lblEstaMarcada');

        document.getElementById('modalVisitaColorMarca').value = 'Sin marcar';

        // Enfermedades (desmarcar todas)
        document.querySelectorAll('input[name="enfermedades"]').forEach(cb => {
            cb.checked = false;
            const lbl = cb.closest('.ap-checkbox-card');
            if (lbl) lbl.classList.remove('is-checked');
        });

        // Labores (por defecto Aplicación de cera y Alimentación)
        document.querySelectorAll('input[name="labores"]').forEach(cb => {
            if (cb.value === 'Aplicación de cera' || cb.value === 'Alimentación') {
                cb.checked = true;
                const lbl = cb.closest('.ap-checkbox-card');
                if (lbl) lbl.classList.add('is-checked');
            } else {
                cb.checked = false;
                const lbl = cb.closest('.ap-checkbox-card');
                if (lbl) lbl.classList.remove('is-checked');
            }
        });

        document.getElementById('modalVisitaBackdrop').classList.add('is-open');
    }

    function openEditVisitaModal(id) {
        const item = inspeccionesList.find(v => v.id === id);
        if (!item) return;

        document.getElementById('modalVisitaTitle').textContent = 'Editar visita';
        document.getElementById('modalVisitaId').value = item.id;
        document.getElementById('modalVisitaFecha').value = item.fecha;
        document.getElementById('modalVisitaColmena').value = item.colmena;
        document.getElementById('modalVisitaResponsable').value = item.responsable;
        document.getElementById('modalVisitaMielKg').value = item.mielKg;
        document.getElementById('modalVisitaAlimentacion').value = item.alimenSuministrada || '';
        document.getElementById('modalVisitaObservaciones').value = item.notas || '';

        setEstadoGeneral(item.estadoGeneral);
        setPresenciaMiel(item.presenciaMiel);

        // Reina
        const chkReina = document.getElementById('chkSeVioReina');
        chkReina.checked = !!item.reinaVista;
        toggleCheckboxCard(chkReina, 'lblSeVioReina');

        const chkMarcada = document.getElementById('chkEstaMarcada');
        chkMarcada.checked = !!item.reinaMarcada;
        toggleCheckboxCard(chkMarcada, 'lblEstaMarcada');

        document.getElementById('modalVisitaColorMarca').value = item.colorMarca || 'Sin marcar';

        // Enfermedades
        document.querySelectorAll('input[name="enfermedades"]').forEach(cb => {
            const hasIt = (item.enfermedades || []).includes(cb.value);
            cb.checked = hasIt;
            const lbl = cb.closest('.ap-checkbox-card');
            if (lbl) {
                if (hasIt) lbl.classList.add('is-checked');
                else lbl.classList.remove('is-checked');
            }
        });

        // Labores
        document.querySelectorAll('input[name="labores"]').forEach(cb => {
            const hasIt = (item.labores || []).includes(cb.value);
            cb.checked = hasIt;
            const lbl = cb.closest('.ap-checkbox-card');
            if (lbl) {
                if (hasIt) lbl.classList.add('is-checked');
                else lbl.classList.remove('is-checked');
            }
        });

        document.getElementById('modalVisitaBackdrop').classList.add('is-open');
    }

    function closeVisitaModal() {
        document.getElementById('modalVisitaBackdrop').classList.remove('is-open');
    }

    function saveVisitaForm(e) {
        e.preventDefault();

        const id = document.getElementById('modalVisitaId').value;
        const fecha = document.getElementById('modalVisitaFecha').value.trim();
        const colmena = document.getElementById('modalVisitaColmena').value.trim();
        const responsable = document.getElementById('modalVisitaResponsable').value.trim();
        const estadoGeneral = document.getElementById('modalVisitaEstadoGeneral').value;
        const presenciaMiel = document.getElementById('modalVisitaPresenciaMiel').value;
        const mielKg = parseFloat(document.getElementById('modalVisitaMielKg').value) || 0;
        
        const reinaVista = document.getElementById('chkSeVioReina').checked;
        const reinaMarcada = document.getElementById('chkEstaMarcada').checked;
        const colorMarca = document.getElementById('modalVisitaColorMarca').value;

        // Enfermedades
        const enfermedades = [];
        document.querySelectorAll('input[name="enfermedades"]:checked').forEach(cb => {
            enfermedades.push(cb.value);
        });

        // Labores
        const labores = [];
        document.querySelectorAll('input[name="labores"]:checked').forEach(cb => {
            labores.push(cb.value);
        });

        const alimenSuministrada = document.getElementById('modalVisitaAlimentacion').value.trim();
        const notas = document.getElementById('modalVisitaObservaciones').value.trim();

        if (id) {
            // Editar existente
            const item = inspeccionesList.find(v => v.id === parseInt(id));
            if (item) {
                item.fecha = fecha;
                item.colmena = colmena;
                item.responsable = responsable;
                item.estadoGeneral = estadoGeneral;
                item.presenciaMiel = presenciaMiel;
                item.mielKg = mielKg;
                item.reinaVista = reinaVista;
                item.reinaMarcada = reinaMarcada;
                item.colorMarca = colorMarca;
                item.enfermedades = enfermedades;
                item.labores = labores;
                item.alimenSuministrada = alimenSuministrada;
                item.notas = notas;
                showToast(`Visita ${item.regNumber} actualizada con éxito.`);
            }
        } else {
            // Crear nueva
            const maxReg = inspeccionesList.reduce((max, v) => {
                const match = v.regNumber.match(/REG-(\d+)/);
                if (match) {
                    const num = parseInt(match[1]);
                    return num > max ? num : max;
                }
                return max;
            }, 0);
            const nextReg = `REG-${String(maxReg + 1).padStart(4, '0')}`;
            const newId = inspeccionesList.length ? Math.max(...inspeccionesList.map(v => v.id)) + 1 : 1;

            inspeccionesList.unshift({
                id: newId,
                regNumber: nextReg,
                colmena: colmena,
                fecha: fecha,
                estadoGeneral: estadoGeneral,
                presenciaMiel: presenciaMiel,
                mielKg: mielKg,
                enfermedades: enfermedades,
                responsable: responsable,
                reinaVista: reinaVista,
                reinaMarcada: reinaMarcada,
                colorMarca: colorMarca,
                labores: labores,
                alimenSuministrada: alimenSuministrada,
                notas: notas
            });

            showToast(`Visita ${nextReg} registrada con éxito.`);

            // Envío asíncrono para persistencia en backend
            const dateParts = fecha.split('/');
            const formattedIso = dateParts.length === 3 ? `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}` : new Date().toISOString().split('T')[0];

            fetch("{{ route('apicola.aprendiz.inspecciones.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    registration_number: nextReg,
                    colmena_code: colmena,
                    date: formattedIso,
                    responsable: responsable,
                    general_state: estadoGeneral,
                    honey_presence: presenciaMiel,
                    harvested_honey_kg: mielKg,
                    queen_seen: reinaVista,
                    queen_marked: reinaMarcada,
                    queen_color: colorMarca,
                    diseases: enfermedades,
                    tasks: labores,
                    feed_supplied: alimenSuministrada,
                    notes: notas
                })
            }).catch(err => console.log('Registro local exitoso'));
        }

        closeVisitaModal();
        renderVisitasTable();
        updateInspeccionMetrics();
    }

    // ==================== DETALLE DE VISITA ====================
    function openVisitaDetailModal(id) {
        const item = inspeccionesList.find(v => v.id === id);
        if (!item) return;

        document.getElementById('detailVisitaRegNum').textContent = item.regNumber;
        document.getElementById('detailVisitaColmena').textContent = `Inspección ${item.colmena}`;
        document.getElementById('detailVisitaFecha').textContent = item.fecha;

        const badgeGen = document.getElementById('detailVisitaEstadoGen');
        badgeGen.textContent = item.estadoGeneral;
        badgeGen.className = 'ap-status-badge ' + (item.estadoGeneral === 'Bueno' ? 'bueno' : (item.estadoGeneral === 'Regular' ? 'regular' : 'alerta'));

        const badgeMiel = document.getElementById('detailVisitaPresMiel');
        badgeMiel.textContent = item.presenciaMiel;
        badgeMiel.className = 'ap-status-badge ' + (item.presenciaMiel === 'Bueno' ? 'bueno' : (item.presenciaMiel === 'Regular' ? 'regular' : 'alerta'));

        document.getElementById('detailVisitaMielKg').textContent = `${parseFloat(item.mielKg).toFixed(1)} kg`;
        document.getElementById('detailVisitaResp').textContent = item.responsable;

        const badgeEnf = document.getElementById('detailVisitaEnfermedades');
        if (item.enfermedades && item.enfermedades.length > 0) {
            badgeEnf.textContent = item.enfermedades.join(', ');
            badgeEnf.className = 'ap-status-badge alerta';
        } else {
            badgeEnf.textContent = 'Ninguna';
            badgeEnf.className = 'ap-status-badge bueno';
        }

        let reinaStr = item.reinaVista ? 'Vista' : 'No vista';
        if (item.reinaMarcada) reinaStr += ` (Marcada: ${item.colorMarca})`;
        document.getElementById('detailVisitaReinaInfo').textContent = reinaStr;

        document.getElementById('detailVisitaLabores').textContent = (item.labores && item.labores.length > 0) ? item.labores.join(', ') : 'Ninguna';
        document.getElementById('detailVisitaNotas').textContent = item.notas || 'Sin observaciones adicionales.';

        document.getElementById('btnEditFromVisitaDetail').onclick = () => {
            closeVisitaDetailModal();
            openEditVisitaModal(item.id);
        };

        document.getElementById('modalVisitaDetailBackdrop').classList.add('is-open');
    }

    function closeVisitaDetailModal() {
        document.getElementById('modalVisitaDetailBackdrop').classList.remove('is-open');
    }
</script>
@endsection
