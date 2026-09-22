@extends('apicola::Admin.layout')

@section('title', 'Gestión de Colmenas • SENA APÍCOLA')

@section('breadcrumb')
    <a href="{{ route('apicola.admin.colmenas.index') }}">Colmenas</a>
    <span>/</span>
    <span class="active-page">Gestión de colmenas</span>
@endsection

@section('content')
    <!-- Encabezado de la vista con perfil apicultor -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">COLMENAS</span>
            <h1 class="ap-view-main-title">Gestión de colmenas</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar" style="background: #047857;">SB</div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">Sergio Barrera</span>
                <span class="ap-user-chip-role">Rol: Apicultor</span>
            </div>
        </div>
    </div>

    <!-- 4 Tarjetas de Métricas (Exactas al diseño) -->
    <div class="ap-metrics-grid">
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">COLMENAS REGISTRADAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricTotal">42</div>
            <div class="ap-metric-card-sub">Apiario SENA La Angostura</div>
        </div>

        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">ACTIVAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricActivas">36</div>
            <div class="ap-metric-card-sub">En producción</div>
        </div>

        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">EN REVISIÓN</div>
            <div class="ap-metric-card-num" id="colmenaMetricRevision">4</div>
            <div class="ap-metric-card-sub">Requieren seguimiento</div>
        </div>

        <div class="ap-metric-card border-red">
            <div class="ap-metric-card-title">INACTIVAS</div>
            <div class="ap-metric-card-num" id="colmenaMetricInactivas">2</div>
            <div class="ap-metric-card-sub">Fuera de operación</div>
        </div>
    </div>

    <!-- Sección: Listado de Colmenas -->
    <div class="ap-list-section">
        <div class="ap-list-top-bar">
            <h2 class="ap-list-title">Listado de colmenas</h2>
            <p class="ap-list-desc">Colmenas del Apiario SENA La Angostura.</p>
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
                    <option value="Apiario La Angostura" selected>Apiario SENA La Angostura</option>
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

        <!-- Tabla de Colmenas -->
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
                    Mostrando <strong id="colmenasShownCount">0</strong> de <strong id="colmenasTotalCount">0</strong> colmenas
                </div>
                <div>
                    Gestión técnica de colmenas • Centro La Angostura
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- ==================== MODAL: NUEVA / EDITAR COLMENA (DISEÑO EXACTO A LA CAPTURA) ==================== -->
    <div class="ap-modal-backdrop" id="modalColmenaBackdrop">
        <div class="ap-modal-window" style="max-width: 500px; border-radius: 18px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="ap-modal-body" style="padding: 2.2rem 2.2rem 1.75rem;">
                <h2 class="ap-modal-title" id="modalColmenaTitle" style="font-size: 1.6rem; font-weight: 900; color: #0f172a; margin: 0 0 4px 0; letter-spacing: -0.02em;">Nueva colmena</h2>
                <p style="font-size: 0.88rem; color: #64748b; margin: 0 0 1.5rem 0;">Toda colmena pertenece a un apiario activo.</p>

                <form id="formColmena" onsubmit="saveColmenaForm(event)">
                    <input type="hidden" id="modalColmenaId" value="">

                    <!-- Fila 1: CÓDIGO (izq) y FECHA DE INSTALACIÓN (der) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 1.25rem;">
                        <div>
                            <label class="ap-field-label" for="modalColmenaCode" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">CÓDIGO</label>
                            <input type="text" id="modalColmenaCode" class="ap-field-input" value="COL-049" placeholder="COL-049" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a;">
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalColmenaFecha" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">FECHA DE INSTALACIÓN</label>
                            <input type="date" id="modalColmenaFecha" class="ap-field-input" value="2026-08-20" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a;">
                        </div>
                    </div>

                    <!-- Fila 2: APIARIO (ancho completo) -->
                    <div class="ap-form-field" style="margin-bottom: 1.25rem;">
                        <label class="ap-field-label" for="modalColmenaApiario" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">APIARIO</label>
                        <select id="modalColmenaApiario" class="ap-field-input" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a; background: #ffffff;">
                            <option value="Apiario La Angostura" selected>Apiario La Angostura</option>
                            <option value="Apiario Los Pinos">Apiario Los Pinos</option>
                            <option value="Apiario El Cedro">Apiario El Cedro</option>
                            <option value="Apiario San José">Apiario San José</option>
                            <option value="Apiario La Esperanza">Apiario La Esperanza</option>
                        </select>
                        <p class="ap-field-hint" style="color: #94a3b8; font-size: 0.76rem; margin-top: 5px; margin-bottom: 0;">Solo se listan apiarios activos.</p>
                    </div>

                    <!-- Fila 3: TIPO DE ABEJA (izq) y ESTADO (der) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 1.25rem;">
                        <div>
                            <label class="ap-field-label" for="modalColmenaTipoAbeja" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">TIPO DE ABEJA</label>
                            <select id="modalColmenaTipoAbeja" class="ap-field-input" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a; background: #ffffff;">
                                <option value="Apis mellifera" selected>Apis mellifera</option>
                                <option value="Melipona eburnea">Melipona eburnea</option>
                                <option value="Tetragonisca angustula">Tetragonisca angustula</option>
                                <option value="Scaptotrigona pectoralis">Scaptotrigona pectoralis</option>
                            </select>
                        </div>
                        <div>
                            <label class="ap-field-label" for="modalColmenaEstado" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">ESTADO</label>
                            <select id="modalColmenaEstado" class="ap-field-input" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a; background: #ffffff;">
                                <option value="Activa" selected>Activa</option>
                                <option value="En revisión">En revisión</option>
                                <option value="Inactiva">Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 4: RESPONSABLE (ancho completo) -->
                    <div class="ap-form-field" style="margin-bottom: 1.5rem;">
                        <label class="ap-field-label" for="modalColmenaResponsable" style="color: #64748b; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 6px; display: block;">RESPONSABLE</label>
                        <select id="modalColmenaResponsable" class="ap-field-input" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.90rem; color: #0f172a; background: #ffffff;">
                            <option value="Jhon Ramírez Ortiz" selected>Jhon Ramírez Ortiz</option>
                            <option value="Sergio Barrera">Sergio Barrera</option>
                            <option value="Aprendiz SENA">Aprendiz SENA</option>
                        </select>
                    </div>

                    <!-- Botones footer (idénticos a la captura) -->
                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 1.75rem;">
                        <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaModal()" style="background: #ffffff; color: #475569; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; cursor: pointer;">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" id="btnSaveColmena" style="background: #047857; color: #ffffff; border: none; padding: 10px 22px; border-radius: 10px; font-size: 0.88rem; font-weight: 700; cursor: pointer;">Guardar colmena</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: DETALLE DE COLMENA ==================== -->
    <div class="ap-modal-backdrop" id="modalColmenaDetailBackdrop">
        <div class="ap-modal-window">
            <div class="ap-modal-body">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" id="colmenaDetailBadge">DETALLE DE LA COLMENA</span>
                        <h2 class="ap-modal-title" id="colmenaDetailCode" style="margin-bottom:0;">COL-011</h2>
                    </div>
                    <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaDetailModal()">✕</button>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; margin-bottom:1.25rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">APIARIO</span>
                            <strong id="colmenaDetailApiario" style="font-size:0.95rem; color:#0f172a;">-</strong>
                        </div>
                        <div>
                            <span class="ap-field-label">ESTADO</span>
                            <span id="colmenaDetailStatusBadge" class="ap-status-badge activa">Activa</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                        <div>
                            <span class="ap-field-label">RESPONSABLE</span>
                            <span id="colmenaDetailResp" style="font-size:0.88rem; color:#334155; font-weight:600;">-</span>
                        </div>
                        <div>
                            <span class="ap-field-label">FECHA DE INSTALACIÓN</span>
                            <span id="colmenaDetailInstall" style="font-size:0.88rem; color:#334155;">-</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <span class="ap-field-label">ÚLTIMA VISITA</span>
                            <span id="colmenaDetailLastVisit" style="font-size:0.88rem; color:#059669; font-weight:700;">-</span>
                        </div>
                        <div>
                            <span class="ap-field-label">TIPO DE CAJA</span>
                            <span style="font-size:0.88rem; color:#334155;">Langstroth Estándar</span>
                        </div>
                    </div>
                </div>

                <p id="colmenaDetailNotas" style="font-size:0.85rem; color:#475569; line-height:1.5; margin-bottom:1.25rem;"></p>

                <div class="ap-modal-footer" style="padding:0; border:none; justify-content:space-between;">
                    <a href="#" id="linkIrInspeccionColmena" class="ap-btn-outline-green" style="font-size:0.84rem;">
                        <i class="fas fa-clipboard-check"></i> Ver historial de inspecciones
                    </a>
                    <div>
                        <button type="button" class="ap-btn-modal-cancel" onclick="closeColmenaDetailModal()">Cerrar</button>
                        <button type="button" class="ap-btn-modal-save" id="btnEditFromColmenaDetail">Editar colmena</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // ==================== ESTADO Y DATOS DE COLMENAS ====================
    let colmenasList = [
        {
            id: 11,
            code: "COL-011",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "12/03/2026",
            ultimaVisita: "27/05/2026",
            estado: "Activa",
            notas: "Colmena vigorosa con excelente postura de reina y reservas óptimas de miel y polen."
        },
        {
            id: 12,
            code: "COL-012",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "12/03/2026",
            ultimaVisita: "27/05/2026",
            estado: "Activa",
            notas: "Cámara de cría completa. Sin signos de enfermedades ni ácaros."
        },
        {
            id: 13,
            code: "COL-013",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "02/04/2026",
            ultimaVisita: "27/05/2026",
            estado: "Activa",
            notas: "Colonia en expansión rápida; se instaló segunda alza melaria."
        },
        {
            id: 14,
            code: "COL-014",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "18/02/2026",
            ultimaVisita: "20/05/2026",
            estado: "En revisión",
            notas: "Se observó baja postura y celda real en desarrollo. Programada revisión de recambio de reina."
        },
        {
            id: 15,
            code: "COL-015",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "18/02/2026",
            ultimaVisita: "20/05/2026",
            estado: "Activa",
            notas: "Colmena productiva, comportamiento dócil y buen acopio de propóleos."
        },
        {
            id: 16,
            code: "COL-016",
            apiario: "Apiario La Angostura",
            responsable: "Sergio Barrera",
            instalacion: "05/01/2026",
            ultimaVisita: "02/04/2026",
            estado: "Inactiva",
            notas: "Colmena colapsada por enjambrazón previa. Material desinfectado y disponible para nuevo núcleo."
        }
    ];

    function generateInitialColmenas() {
        const apiariosNombres = ["Apiario La Angostura"];
        const apicultores = ["Sergio Barrera"];

        // Añadir colmenas 1 a 10
        for (let i = 1; i <= 10; i++) {
            const codeNum = i < 10 ? `00${i}` : `0${i}`;
            colmenasList.push({
                id: i,
                code: `COL-${codeNum}`,
                apiario: "Apiario La Angostura",
                responsable: "Sergio Barrera",
                instalacion: `${(i % 25) + 1}/01/2026`,
                ultimaVisita: `${(i % 20) + 5}/05/2026`,
                estado: "Activa",
                notas: `Colmena ${codeNum} con monitoreo fitosanitario rutinario al día.`
            });
        }

        // Añadir colmenas 17 a 42
        for (let i = 17; i <= 42; i++) {
            let st = "Activa";
            if (i === 22 || i === 28 || i === 35) st = "En revisión";
            if (i === 39) st = "Inactiva";

            colmenasList.push({
                id: i,
                code: `COL-0${i}`,
                apiario: "Apiario La Angostura",
                responsable: "Sergio Barrera",
                instalacion: `${(i % 26) + 1}/02/2026`,
                ultimaVisita: `${(i % 18) + 10}/05/2026`,
                estado: st,
                notas: `Colmena 0${i} en inspección técnica semanal del centro La Angostura.`
            });
        }

        colmenasList.sort((a, b) => a.code.localeCompare(b.code));
    }

    document.addEventListener('DOMContentLoaded', () => {
        generateInitialColmenas();
        renderColmenasTable();
        updateColmenasMetrics();
    });

    function updateColmenasMetrics() {
        const total = colmenasList.length;
        const activas = colmenasList.filter(c => c.estado === 'Activa').length;
        const revision = colmenasList.filter(c => c.estado === 'En revisión').length;
        const inactivas = colmenasList.filter(c => c.estado === 'Inactiva').length;

        document.getElementById('colmenaMetricTotal').textContent = total;
        document.getElementById('colmenaMetricActivas').textContent = activas;
        document.getElementById('colmenaMetricRevision').textContent = revision;
        document.getElementById('colmenaMetricInactivas').textContent = inactivas;

        document.getElementById('colmenasTotalCount').textContent = total;
    }

    function filterColmenas() {
        renderColmenasTable();
    }

    function renderColmenasTable() {
        const searchVal = (document.getElementById('colmenaSearchInput')?.value || '').toLowerCase().trim();
        const apiarioVal = document.getElementById('colmenaApiarioFilter')?.value || 'all';
        const estadoVal = document.getElementById('colmenaEstadoFilter')?.value || 'all';

        const filtered = colmenasList.filter(item => {
            const matchSearch = item.code.toLowerCase().includes(searchVal) ||
                                item.responsable.toLowerCase().includes(searchVal) ||
                                item.apiario.toLowerCase().includes(searchVal);
            const matchApiario = (apiarioVal === 'all') || (item.apiario === apiarioVal);
            const matchEstado = (estadoVal === 'all') || (item.estado === estadoVal);

            return matchSearch && matchApiario && matchEstado;
        });

        const tbody = document.getElementById('colmenasTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        document.getElementById('colmenasShownCount').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align:center; padding: 2.5rem 1rem; color: #94a3b8;">
                        <i class="fas fa-search" style="font-size: 1.5rem; margin-bottom: 0.5rem; display:block;"></i>
                        No se encontraron colmenas con los filtros aplicados.
                    </td>
                </tr>
            `;
            return;
        }

        filtered.forEach(c => {
            const tr = document.createElement('tr');
            
            let badgeClass = 'activa';
            if (c.estado === 'En revisión') badgeClass = 'en-revision';
            if (c.estado === 'Inactiva') badgeClass = 'inactiva';

            const isInactive = c.estado === 'Inactiva';
            const toggleActionText = isInactive ? 'Activar' : 'Desactivar';
            const toggleActionClass = isInactive ? 'activate' : 'deactivate';

            const inspectionUrl = "{{ url('apicola/colmenas') }}/" + c.code + "/inspecciones";

            tr.innerHTML = `
                <td style="font-weight: 800; color: #0f172a;">${c.code}</td>
                <td style="color: #334155;">${c.apiario}</td>
                <td style="color: #1e293b; font-weight: 500;">${c.tipoAbeja || 'Apis mellifera'}</td>
                <td style="color: #334155;">${c.responsable}</td>
                <td style="color: #64748b;">${c.instalacion}</td>
                <td style="color: #64748b;">${c.ultimaVisita || '-'}</td>
                <td>
                    <span class="ap-status-badge ${badgeClass}">${c.estado}</span>
                </td>
                <td>
                    <div class="ap-actions-group">
                        <button type="button" class="ap-action-btn view" onclick="openColmenaDetailModal(${c.id})">Ver detalle</button>
                        <button type="button" class="ap-action-btn edit" onclick="openEditColmenaModal(${c.id})">Editar</button>
                        <a href="${inspectionUrl}" class="ap-action-btn" style="color: #0284c7; font-weight:600;" title="Ir a inspección de ${c.code}">Inspección</a>
                        <button type="button" class="ap-action-btn ${toggleActionClass}" onclick="toggleColmenaStatus(${c.id})">${toggleActionText}</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // ==================== MODAL DE NUEVA / EDITAR COLMENA ====================
    function openNewColmenaModal() {
        document.getElementById('modalColmenaTitle').textContent = 'Nueva colmena';
        document.getElementById('modalColmenaId').value = '';
        
        const maxNum = colmenasList.reduce((max, c) => {
            const match = c.code.match(/COL-(\d+)/);
            if (match) {
                const num = parseInt(match[1]);
                return num > max ? num : max;
            }
            return max;
        }, 0);
        const nextCode = maxNum >= 48 ? `COL-0${maxNum + 1}` : 'COL-049';
        document.getElementById('modalColmenaCode').value = nextCode;

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('modalColmenaFecha').value = today;
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
        document.getElementById('modalColmenaApiario').value = item.apiario;
        document.getElementById('modalColmenaTipoAbeja').value = item.tipoAbeja || 'Apis mellifera';
        document.getElementById('modalColmenaEstado').value = item.estado;
        document.getElementById('modalColmenaResponsable').value = item.responsable;
        
        let dateVal = item.instalacion;
        if (dateVal && dateVal.includes('/')) {
            const parts = dateVal.split('/');
            dateVal = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
        }
        document.getElementById('modalColmenaFecha').value = dateVal || '';

        document.getElementById('modalColmenaBackdrop').classList.add('is-open');
    }

    function closeColmenaModal() {
        document.getElementById('modalColmenaBackdrop').classList.remove('is-open');
    }

    function saveColmenaForm(e) {
        e.preventDefault();

        const id = document.getElementById('modalColmenaId').value;
        const code = document.getElementById('modalColmenaCode').value.trim();
        const apiario = document.getElementById('modalColmenaApiario').value;
        const tipoAbeja = document.getElementById('modalColmenaTipoAbeja').value;
        const rawFecha = document.getElementById('modalColmenaFecha').value;
        const responsable = document.getElementById('modalColmenaResponsable').value;
        const estado = document.getElementById('modalColmenaEstado').value;

        let instalacionFormatted = rawFecha;
        if (rawFecha && rawFecha.includes('-')) {
            const parts = rawFecha.split('-');
            instalacionFormatted = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        if (id) {
            const item = colmenasList.find(c => c.id === parseInt(id));
            if (item) {
                item.code = code;
                item.apiario = apiario;
                item.tipoAbeja = tipoAbeja;
                item.instalacion = instalacionFormatted;
                item.responsable = responsable;
                item.estado = estado;
                showToast(`Colmena "${code}" actualizada correctamente.`);
            }
        } else {
            const exists = colmenasList.some(c => c.code.toLowerCase() === code.toLowerCase());
            if (exists) {
                showToast(`Aviso: El código "${code}" ya se encuentra registrado.`);
                return;
            }

            const newId = colmenasList.length ? Math.max(...colmenasList.map(c => c.id)) + 1 : 1;
            const todayFormatted = new Date().toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric' });
            
            colmenasList.unshift({
                id: newId,
                code: code,
                apiario: apiario,
                tipoAbeja: tipoAbeja,
                responsable: responsable,
                instalacion: instalacionFormatted,
                ultimaVisita: todayFormatted,
                estado: estado,
                notas: "Colmena registrada recientemente a través de la gestión de colmenas."
            });
            showToast(`Colmena "${code}" guardada con éxito.`);
        }

        closeColmenaModal();
        renderColmenasTable();
        updateColmenasMetrics();
    }

    // ==================== DETALLE DE COLMENA ====================
    function openColmenaDetailModal(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;

        document.getElementById('colmenaDetailCode').textContent = item.code;
        document.getElementById('colmenaDetailApiario').textContent = item.apiario;
        document.getElementById('colmenaDetailResp').textContent = item.responsable;
        document.getElementById('colmenaDetailInstall').textContent = item.instalacion;
        document.getElementById('colmenaDetailLastVisit').textContent = item.ultimaVisita || 'Sin visita reciente';
        document.getElementById('colmenaDetailNotas').textContent = item.notas || 'Sin observaciones fitosanitarias registradas.';

        const badge = document.getElementById('colmenaDetailStatusBadge');
        badge.textContent = item.estado;
        badge.className = 'ap-status-badge ' + (item.estado === 'Activa' ? 'activa' : (item.estado === 'En revisión' ? 'en-revision' : 'inactiva'));

        document.getElementById('linkIrInspeccionColmena').href = "{{ url('apicola/colmenas') }}/" + item.code + "/inspecciones";

        document.getElementById('btnEditFromColmenaDetail').onclick = () => {
            closeColmenaDetailModal();
            openEditColmenaModal(item.id);
        };

        document.getElementById('modalColmenaDetailBackdrop').classList.add('is-open');
    }

    function closeColmenaDetailModal() {
        document.getElementById('modalColmenaDetailBackdrop').classList.remove('is-open');
    }

    // ==================== ALTERNAR ESTADO COLMENA ====================
    function toggleColmenaStatus(id) {
        const item = colmenasList.find(c => c.id === id);
        if (!item) return;

        if (item.estado === 'Inactiva') {
            item.estado = 'Activa';
            showToast(`Colmena "${item.code}" reactivada en producción.`);
        } else {
            item.estado = 'Inactiva';
            showToast(`Colmena "${item.code}" desactivada. Conserva su trazabilidad.`);
        }

        renderColmenasTable();
        updateColmenasMetrics();
    }
</script>
@endsection
