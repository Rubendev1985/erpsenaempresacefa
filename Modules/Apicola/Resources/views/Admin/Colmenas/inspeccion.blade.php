@extends('apicola::Admin.layout')

@section('title', 'Registro e Inspección • ' . ($selectedColmena ?? 'COL-013') . ' • SENA APÍCOLA')

@section('breadcrumb')
    <a href="{{ route('apicola.admin.colmenas.index') }}">Colmenas</a>
    <span>/</span>
    <span style="font-weight: 700; color: #059669;" id="currentColmenaCode">{{ $selectedColmena ?? 'COL-013' }}</span>
    <span>/</span>
    <span class="active-page">Registro e inspección</span>
@endsection

@section('content')
    <!-- Encabezado de la vista con perfil apicultor -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">
                COLMENAS / <span id="colmenaTag">{{ $selectedColmena ?? 'COL-013' }}</span> / REGISTRO E INSPECCIÓN
            </span>
            <h1 class="ap-view-main-title">Registro e inspección</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar" style="background: #047857;">SB</div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">Sergio Barrera</span>
                <span class="ap-user-chip-role">Rol: Apicultor</span>
            </div>
        </div>
    </div>

    <!-- 3 Tarjetas de Métricas -->
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

            <!-- Selector de colmena rápido si desea cambiar -->
            <div style="display:flex; align-items:center; gap:8px;">
                <label class="ap-field-label" style="margin-bottom:0;" for="selectChangeColmena">Cambiar colmena:</label>
                <select id="selectChangeColmena" class="ap-filter-select" onchange="changeActiveColmena(this.value)">
                    <option value="COL-013" selected>COL-013 (Apiario SENA La Angostura)</option>
                    <option value="COL-011">COL-011 (Apiario SENA La Angostura)</option>
                    <option value="COL-012">COL-012 (Apiario SENA La Angostura)</option>
                    <option value="COL-014">COL-014 (Apiario SENA La Angostura)</option>
                    <option value="COL-015">COL-015 (Apiario SENA La Angostura)</option>
                    <option value="COL-016">COL-016 (Apiario SENA La Angostura)</option>
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
@endsection

@section('modals')
    <!-- ==================== MODAL: REGISTRAR / EDITAR VISITA ==================== -->
    <div class="ap-modal-backdrop" id="modalVisitaBackdrop">
        <div class="ap-modal-window" style="max-width: 580px;">
            <div class="ap-modal-body" style="padding: 2rem 2.2rem 1.5rem;">
                <h2 class="ap-modal-title" id="modalVisitaTitle" style="font-size: 1.65rem; font-weight: 900; color: #0f172a; margin-bottom: 1.4rem;">Registrar visita</h2>

                <form id="formVisita" onsubmit="saveVisitaForm(event)">
                    <input type="hidden" id="modalVisitaId" value="">

                    <!-- Fila 1: Fecha, Colmena, Responsable -->
                    <div class="ap-coords-row cols-3" style="margin-bottom: 1.25rem; gap: 10px;">
                        <div>
                            <label class="ap-field-label" for="modalVisitaFecha">FECHA</label>
                            <input type="text" id="modalVisitaFecha" class="ap-field-input" value="27/05/2026" required>
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
                            <option value="Sin marcar">Sin marcar</option>
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
                            <input type="checkbox" name="enfermedades" value="Envenamiento" onchange="toggleCheckboxCard(this, 'lblEnfEnvenenadas')">
                            <span>Envenenamiento</span>
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

                    <!-- Footer -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 1.5rem;">
                        <button type="button" class="ap-btn-modal-cancel" onclick="closeVisitaModal()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" id="btnSaveVisita">Guardar visita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: DETALLE DE VISITA ==================== -->
    <div class="ap-modal-backdrop" id="modalVisitaDetailBackdrop">
        <div class="ap-modal-window" style="max-width: 600px;">
            <div class="ap-modal-body">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" id="detailVisitaRegNum">REG-0142</span>
                        <h2 class="ap-modal-title" id="detailVisitaColmena" style="margin-bottom:0;">Inspección Colmena</h2>
                    </div>
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeVisitaDetailModal()">✕</button>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; margin-bottom:1.25rem;">
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
                    <p id="detailVisitaNotas" style="font-size:0.85rem; color:#475569; line-height:1.5; background: #f1f5f9; padding: 10px 14px; border-radius: 8px;"></p>
                </div>

                <div class="ap-modal-footer" style="padding:0; border:none;">
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

    // Dataset exacto a la captura de pantalla
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
        document.getElementById('modalVisitaResponsable').value = 'Sergio Barrera';
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

            showToast(`Visita ${nextReg} registrada exitosamente.`);
        }

        closeVisitaModal();
        renderVisitasTable();
        updateInspeccionMetrics();
    }

    // ==================== MODAL DE DETALLE DE VISITA ====================
    function openVisitaDetailModal(id) {
        const item = inspeccionesList.find(v => v.id === id);
        if (!item) return;

        document.getElementById('detailVisitaRegNum').textContent = item.regNumber;
        document.getElementById('detailVisitaColmena').textContent = `Inspección Colmena ${item.colmena}`;
        document.getElementById('detailVisitaFecha').textContent = item.fecha;
        document.getElementById('detailVisitaResp').textContent = item.responsable;
        document.getElementById('detailVisitaMielKg').textContent = `${parseFloat(item.mielKg).toFixed(1)} kg`;

        const badgeEstGen = document.getElementById('detailVisitaEstadoGen');
        badgeEstGen.textContent = item.estadoGeneral;
        badgeEstGen.className = 'ap-status-badge ' + (item.estadoGeneral === 'Bueno' ? 'bueno' : (item.estadoGeneral === 'Regular' ? 'regular' : 'malo'));

        const badgePresMiel = document.getElementById('detailVisitaPresMiel');
        badgePresMiel.textContent = item.presenciaMiel;
        badgePresMiel.className = 'ap-status-badge ' + (item.presenciaMiel === 'Bueno' ? 'bueno' : (item.presenciaMiel === 'Regular' ? 'regular' : 'malo'));

        const enfContainer = document.getElementById('detailVisitaEnfermedades');
        if (item.enfermedades && item.enfermedades.length > 0) {
            enfContainer.textContent = item.enfermedades.join(', ');
            enfContainer.className = 'ap-status-badge alerta';
        } else {
            enfContainer.textContent = 'Ninguna';
            enfContainer.className = 'ap-status-badge bueno';
        }

        // Reina
        let reinaStr = item.reinaVista ? 'Se vio la reina' : 'No se vio la reina';
        if (item.reinaMarcada) reinaStr += ` • Marcada (${item.colorMarca})`;
        document.getElementById('detailVisitaReinaInfo').textContent = reinaStr;

        // Labores
        let laboresStr = (item.labores && item.labores.length > 0) ? item.labores.join(', ') : 'Ninguna registrada';
        if (item.alimenSuministrada) laboresStr += ` [Alimentación: ${item.alimenSuministrada}]`;
        document.getElementById('detailVisitaLabores').textContent = laboresStr;

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
