@extends('apicola::Admin.layout')

@section('title', 'Gestión de Usuarios • SENA APÍCOLA')

@section('breadcrumb')
    <a href="{{ route('apicola.admin.dashboard') }}">Panel Principal</a>
    <span>/</span>
    <span class="active-page">Gestión de Usuarios</span>
@endsection

@section('styles')
<style>
    /* Estilos para el módulo de Usuarios */
    .ap-metric-card.border-green {
        border-left: 4px solid #059669;
    }
    .ap-metric-card.border-amber {
        border-left: 4px solid #f59e0b;
    }
    .ap-metric-card.border-sky {
        border-left: 4px solid #0284c7;
    }
    .ap-metric-card.border-teal {
        border-left: 4px solid #0d9488;
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

    /* Barra de controles */
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
        gap: 8px;
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
        transition: all 0.2s;
        text-decoration: none;
    }
    .ap-btn-new-item:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.35);
    }

    /* Tabla */
    .ap-table-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow-x: auto;
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

    /* Badges de Roles */
    .ap-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .ap-role-badge.admin {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .ap-role-badge.aprendiz {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .ap-role-badge.superadmin {
        background: #f5f3ff;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
    }

    /* Badges de Estado */
    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.76rem;
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

    /* Avatar de usuario */
    .ap-user-row-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(5, 150, 105, 0.2);
    }
    .ap-user-row-avatar.aprendiz {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 2px 5px rgba(245, 158, 11, 0.2);
    }

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
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .ap-table-action-link:hover {
        color: #047857;
        text-decoration: underline;
    }
    .ap-table-action-link.danger {
        color: #ef4444;
    }
    .ap-table-action-link.danger:hover {
        color: #dc2626;
    }
    .ap-table-action-link.activate {
        color: #0284c7;
    }
    .ap-table-action-link.activate:hover {
        color: #0369a1;
    }

    /* Modal Form Styles */
    .ap-form-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        color: #059669;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 1.25rem 0 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ap-form-section-title:first-of-type {
        margin-top: 0;
    }
    .ap-form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .ap-form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .ap-form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }

    .ap-input-doc-group {
        display: flex;
        gap: 8px;
    }
    .ap-btn-search-doc {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .ap-btn-search-doc:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .ap-person-alert {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.82rem;
        margin-top: 8px;
        display: none;
        align-items: center;
        gap: 8px;
    }
    .ap-person-alert.found {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        display: flex;
    }
    .ap-person-alert.not-found {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        display: flex;
    }

    .ap-role-card-select {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 6px;
    }
    .ap-role-card-option {
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #ffffff;
    }
    .ap-role-card-option:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .ap-role-card-option.selected {
        border-color: #059669;
        background: #ecfdf5;
    }
    .ap-role-card-option.selected .role-radio {
        border-color: #059669;
        background: #059669;
    }
    .role-radio {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        margin-top: 2px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .role-radio::after {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #ffffff;
    }
</style>
@endsection

@section('content')
    <!-- Encabezado de la vista -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">ADMINISTRACIÓN & SEGURIDAD</span>
            <h1 class="ap-view-main-title">Gestión de Usuarios</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar" style="background: #047857;">
                {{ Auth::user()?->initials ?? 'AD' }}
            </div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">
                    {{ Auth::user()?->full_name ?? (Auth::user()?->nickname ?? 'Administrador Apícola') }}
                </span>
                <span class="ap-user-chip-role" style="display:flex; align-items:center; gap:4px;">
                    Rol: {{ Auth::user()?->primary_role ?? 'Administrador Apícola' }}
                </span>
            </div>
        </div>
    </div>

    <!-- 4 Tarjetas de Métricas -->
    <div class="ap-metrics-grid">
        <!-- 1. Total Usuarios -->
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">TOTAL USUARIOS APÍCOLA</div>
            <div class="ap-metric-card-num" id="metricTotal">{{ $metrics['total'] ?? 0 }}</div>
            <div class="ap-metric-card-sub">Cuentas vinculadas al módulo</div>
        </div>

        <!-- 2. Administradores -->
        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">ADMINISTRADORES</div>
            <div class="ap-metric-card-num" id="metricAdmins">{{ $metrics['admins'] ?? 0 }}</div>
            <div class="ap-metric-card-sub">Acceso administrativo completo</div>
        </div>

        <!-- 3. Aprendices -->
        <div class="ap-metric-card border-sky">
            <div class="ap-metric-card-title">APRENDICES</div>
            <div class="ap-metric-card-num" id="metricAprendices">{{ $metrics['aprendices'] ?? 0 }}</div>
            <div class="ap-metric-card-sub">Perfil de consulta y registro técnico</div>
        </div>

        <!-- 4. Usuarios Activos -->
        <div class="ap-metric-card border-teal">
            <div class="ap-metric-card-title">USUARIOS ACTIVOS</div>
            <div class="ap-metric-card-num" id="metricActivos">{{ $metrics['activos'] ?? 0 }}</div>
            <div class="ap-metric-card-sub">Con inicio de sesión habilitado</div>
        </div>
    </div>

    <!-- Sección de Listado de Usuarios -->
    <div class="ap-list-section">
        <div class="ap-list-header">
            <h2 class="ap-list-title">Directorio de Usuarios</h2>
            <p class="ap-list-desc">Consulta, crea y administra los usuarios y roles asignados a la unidad productiva apícola.</p>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="ap-controls-bar">
            <div class="ap-controls-left">
                <!-- Buscador -->
                <div class="ap-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="ap-search-input" placeholder="Buscar por nombre, documento o usuario...">
                </div>

                <!-- Filtro por Rol -->
                <select id="filterRole" class="ap-filter-dropdown">
                    <option value="all">Todos los roles</option>
                    <option value="apicola.admin">Administrador Apícola</option>
                    <option value="apicola.aprendiz">Aprendiz Apícola</option>
                </select>

                <!-- Filtro por Estado -->
                <select id="filterEstado" class="ap-filter-dropdown">
                    <option value="all">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                </select>
            </div>

            <!-- Botón Crear Nuevo Usuario -->
            <button type="button" class="ap-btn-new-item" onclick="abrirModalCrear()">
                <i class="fas fa-user-plus"></i>
                <span>Nuevo Usuario</span>
            </button>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="ap-table-wrap">
            <table class="ap-inv-table" id="usersTable">
                <thead>
                    <tr>
                        <th>PERSONA / TITULAR</th>
                        <th>USUARIO (NICKNAME)</th>
                        <th>CORREO ELECTRÓNICO</th>
                        <th>ROL APÍCOLA</th>
                        <th>ESTADO</th>
                        <th style="text-align: right;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $u)
                        @php
                            $person = $u->person;
                            $fullName = $person ? ($person->first_name . ' ' . $person->first_last_name . ' ' . ($person->second_last_name ?? '')) : ($u->name ?? $u->nickname);
                            $docText = $person ? ($person->document_type . ': ' . $person->document_number) : 'Sin documento registrado';
                            $isAdmin = $u->roles->contains('slug', 'apicola.admin');
                            $isAprendiz = $u->roles->contains('slug', 'apicola.aprendiz');
                            $isActive = !$u->trashed();
                            $initials = $u->initials ?? strtoupper(substr($u->nickname, 0, 2));
                        @endphp
                        <tr data-id="{{ $u->id }}" data-role="{{ $isAdmin ? 'apicola.admin' : ($isAprendiz ? 'apicola.aprendiz' : 'other') }}" data-status="{{ $isActive ? 'activo' : 'inactivo' }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="ap-user-row-avatar {{ $isAprendiz ? 'aprendiz' : '' }}">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">
                                            {{ $fullName }}
                                        </div>
                                        <div style="font-size: 0.78rem; color: #64748b; display: flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-id-card" style="font-size: 0.75rem;"></i> {{ $docText }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #1e293b; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-at" style="color: #94a3b8; font-size: 0.8rem;"></i>
                                    <span>{{ $u->nickname }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.84rem; color: #475569; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-envelope" style="color: #94a3b8; font-size: 0.8rem;"></i>
                                    <span>{{ $u->email }}</span>
                                </div>
                            </td>
                            <td>
                                @if($isAdmin)
                                    <span class="ap-role-badge admin">
                                        <i class="fas fa-shield-halved"></i> Administrador Apícola
                                    </span>
                                @elseif($isAprendiz)
                                    <span class="ap-role-badge aprendiz">
                                        <i class="fas fa-graduation-cap"></i> Aprendiz Apícola
                                    </span>
                                @else
                                    <span class="ap-role-badge superadmin">
                                        <i class="fas fa-user-gear"></i> {{ $u->primary_role }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($isActive)
                                    <span class="badge-status-pill activo">
                                        <i class="fas fa-circle-check"></i> Activo
                                    </span>
                                @else
                                    <span class="badge-status-pill inactivo">
                                        <i class="fas fa-circle-xmark"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="ap-table-actions" style="justify-content: flex-end;">
                                    <button type="button" class="ap-table-action-link" onclick="editarUsuario({{ $u->id }})" title="Editar usuario">
                                        <i class="fas fa-pen-to-square"></i> Editar
                                    </button>
                                    @if(auth()->id() !== $u->id)
                                        @if($isActive)
                                            <button type="button" class="ap-table-action-link danger" onclick="toggleEstadoUsuario({{ $u->id }}, 'desactivar')" title="Desactivar usuario">
                                                <i class="fas fa-user-slash"></i> Desactivar
                                            </button>
                                        @else
                                            <button type="button" class="ap-table-action-link activate" onclick="toggleEstadoUsuario({{ $u->id }}, 'activar')" title="Activar usuario">
                                                <i class="fas fa-user-check"></i> Activar
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2.5rem; color: #64748b;">
                                <i class="fas fa-users" style="font-size: 2.2rem; color: #cbd5e1; margin-bottom: 0.5rem; display: block;"></i>
                                No se encontraron usuarios registrados en el módulo Apícola.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODAL: CREAR / EDITAR USUARIO ==================== -->
    <div class="ap-modal-backdrop" id="modalUsuarioBackdrop">
        <div class="ap-modal-window large" style="max-width: 680px;">
            <div class="ap-modal-body">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                    <div>
                        <span class="ap-view-subtitle-tag" id="modalTag">REGISTRO</span>
                        <h2 class="ap-modal-title" id="modalTitle" style="margin-bottom: 0.2rem;">Registrar Nuevo Usuario</h2>
                    </div>
                    <button type="button" onclick="cerrarModalUsuario()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="modal-subtitle" id="modalSubtitle">
                    Ingresa los datos personales y credenciales para conceder acceso a la plataforma apícola.
                </p>

                <form id="formUsuario" onsubmit="guardarUsuario(event)">
                    @csrf
                    <input type="hidden" id="userId" name="userId" value="">

                    <!-- SECCIÓN 1: DATOS PERSONALES / IDENTIFICACIÓN SICA -->
                    <div class="ap-form-section-title">
                        <i class="fas fa-id-card"></i> 1. Identificación y Persona (SICA)
                    </div>

                    <div class="ap-form-grid-2">
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="document_type">TIPO DE DOCUMENTO *</label>
                            <select id="document_type" name="document_type" class="ap-field-input" required>
                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                                <option value="Cédula de extranjería">Cédula de extranjería</option>
                                <option value="Permiso por protección temporal">Permiso por protección temporal</option>
                            </select>
                        </div>

                        <div class="ap-form-field">
                            <label class="ap-field-label" for="document_number">NÚMERO DE DOCUMENTO *</label>
                            <div class="ap-input-doc-group">
                                <input type="number" id="document_number" name="document_number" class="ap-field-input" placeholder="Ej. 1075258901" required>
                                <button type="button" class="ap-btn-search-doc" id="btnBuscarPersona" onclick="consultarPersona()" title="Consultar en la base de datos SICA">
                                    <i class="fas fa-magnifying-glass"></i>
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas de consulta de persona -->
                    <div id="alertPersonFound" class="ap-person-alert found">
                        <i class="fas fa-check-circle" style="font-size: 1.1rem;"></i>
                        <span id="textPersonFound">Persona encontrada en SICA. Se autocompletaron sus datos personales.</span>
                    </div>
                    <div id="alertPersonNotFound" class="ap-person-alert not-found">
                        <i class="fas fa-info-circle" style="font-size: 1.1rem;"></i>
                        <span>Persona no registrada previamente. Se creará automáticamente su registro en SICA.</span>
                    </div>

                    <div class="ap-form-grid-3" style="margin-top: 12px;">
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="first_name">NOMBRES *</label>
                            <input type="text" id="first_name" name="first_name" class="ap-field-input" placeholder="Ej. Carlos Andrés" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="first_last_name">PRIMER APELLIDO *</label>
                            <input type="text" id="first_last_name" name="first_last_name" class="ap-field-input" placeholder="Ej. Gómez" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="second_last_name">SEGUNDO APELLIDO</label>
                            <input type="text" id="second_last_name" name="second_last_name" class="ap-field-input" placeholder="Ej. Pérez">
                        </div>
                    </div>

                    <!-- SECCIÓN 2: CREDENCIALES DE ACCESO -->
                    <div class="ap-form-section-title">
                        <i class="fas fa-key"></i> 2. Credenciales de la Cuenta
                    </div>

                    <div class="ap-form-grid-2">
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="nickname">NOMBRE DE USUARIO (NICKNAME) *</label>
                            <input type="text" id="nickname" name="nickname" class="ap-field-input" placeholder="Ej. cgomez" required>
                        </div>
                        <div class="ap-form-field">
                            <label class="ap-field-label" for="email">CORREO ELECTRÓNICO *</label>
                            <input type="email" id="email" name="email" class="ap-field-input" placeholder="Ej. cgomez@sena.edu.co" required>
                        </div>
                    </div>

                    <div class="ap-form-field" style="margin-top: 10px;">
                        <label class="ap-field-label" for="password" id="labelPassword">CONTRASEÑA *</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="ap-field-input" placeholder="Mínimo 6 caracteres" style="padding-right: 40px;" required>
                            <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
                                <i class="fas fa-eye" id="iconEye"></i>
                            </button>
                        </div>
                        <div id="passwordHint" style="font-size: 0.76rem; color: #64748b; margin-top: 4px; display: none;">
                            Deja este campo en blanco si no deseas modificar la contraseña actual.
                        </div>
                    </div>

                    <!-- SECCIÓN 3: ASIGNACIÓN DE ROL -->
                    <div class="ap-form-section-title">
                        <i class="fas fa-user-shield"></i> 3. Rol en el Módulo Apícola
                    </div>

                    <div class="ap-role-card-select">
                        <input type="hidden" name="role_slug" id="role_slug" value="apicola.aprendiz">

                        <!-- Opción Aprendiz -->
                        <div class="ap-role-card-option selected" id="optRoleAprendiz" onclick="seleccionarRol('apicola.aprendiz')">
                            <div class="role-radio"></div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-graduation-cap" style="color: #d97706;"></i> Aprendiz Apícola
                                </div>
                                <div style="font-size: 0.78rem; color: #64748b; line-height: 1.35; margin-top: 4px;">
                                    Acceso a consultar colmenas y diligenciar inspecciones y registros técnicos.
                                </div>
                            </div>
                        </div>

                        <!-- Opción Administrador -->
                        <div class="ap-role-card-option" id="optRoleAdmin" onclick="seleccionarRol('apicola.admin')">
                            <div class="role-radio"></div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-shield-halved" style="color: #059669;"></i> Administrador Apícola
                                </div>
                                <div style="font-size: 0.78rem; color: #64748b; line-height: 1.35; margin-top: 4px;">
                                    Control total: apiarios, colmenas, inventario, calendario y administración de usuarios.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones del Modal -->
                    <div class="ap-modal-footer" style="padding: 1.5rem 0 0; margin-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="ap-btn-modal-cancel" onclick="cerrarModalUsuario()">Cancelar</button>
                        <button type="submit" class="ap-btn-modal-save" style="background: #059669;" id="btnSubmitUsuario">
                            <i class="fas fa-check"></i> <span id="btnSubmitText">Guardar Usuario</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const routeStore = '{{ route("apicola.admin.usuarios.store") }}';
    const routeSearchPerson = '{{ route("apicola.admin.usuarios.search-person") }}';
    const baseUsersUrl = '{{ url("apicola/usuarios") }}';

    // Selección interactiva de rol
    function seleccionarRol(slug) {
        document.getElementById('role_slug').value = slug;
        const optAprendiz = document.getElementById('optRoleAprendiz');
        const optAdmin = document.getElementById('optRoleAdmin');

        if (slug === 'apicola.admin') {
            optAdmin.classList.add('selected');
            optAprendiz.classList.remove('selected');
        } else {
            optAprendiz.classList.add('selected');
            optAdmin.classList.remove('selected');
        }
    }

    // Alternar visibilidad de contraseña
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('iconEye');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    // Abrir Modal para Registrar Nuevo Usuario
    function abrirModalCrear() {
        document.getElementById('formUsuario').reset();
        document.getElementById('userId').value = '';
        document.getElementById('modalTag').innerText = 'REGISTRO';
        document.getElementById('modalTitle').innerText = 'Registrar Nuevo Usuario';
        document.getElementById('modalSubtitle').innerText = 'Ingresa los datos personales y credenciales para conceder acceso a la plataforma apícola.';
        document.getElementById('btnSubmitText').innerText = 'Registrar Usuario';
        
        // Contraseña obligatoria en creación
        document.getElementById('password').required = true;
        document.getElementById('labelPassword').innerText = 'CONTRASEÑA *';
        document.getElementById('passwordHint').style.display = 'none';

        // Documento editable
        document.getElementById('document_number').readOnly = false;
        document.getElementById('btnBuscarPersona').style.display = 'inline-flex';

        // Ocultar alertas de persona
        document.getElementById('alertPersonFound').classList.remove('found');
        document.getElementById('alertPersonNotFound').classList.remove('not-found');

        // Rol por defecto: Aprendiz
        seleccionarRol('apicola.aprendiz');

        document.getElementById('modalUsuarioBackdrop').classList.add('is-open');
    }

    // Cerrar modal
    function cerrarModalUsuario() {
        document.getElementById('modalUsuarioBackdrop').classList.remove('is-open');
    }

    // Consultar si la persona ya existe en SICA
    async function consultarPersona() {
        const docNumber = document.getElementById('document_number').value.trim();
        if (!docNumber) {
            Swal.fire({
                icon: 'warning',
                title: 'Número requerido',
                text: 'Por favor escribe un número de documento para buscar.',
                confirmButtonColor: '#059669'
            });
            return;
        }

        const btn = document.getElementById('btnBuscarPersona');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        try {
            const response = await fetch(routeSearchPerson, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ document_number: docNumber })
            });

            const data = await response.json();

            const alertFound = document.getElementById('alertPersonFound');
            const alertNotFound = document.getElementById('alertPersonNotFound');

            if (data.success && data.found) {
                // Autocompletar datos
                const p = data.person;
                if (p.document_type) document.getElementById('document_type').value = p.document_type;
                if (p.first_name) document.getElementById('first_name').value = p.first_name;
                if (p.first_last_name) document.getElementById('first_last_name').value = p.first_last_name;
                if (p.second_last_name) document.getElementById('second_last_name').value = p.second_last_name;
                if (p.personal_email && !document.getElementById('email').value) {
                    document.getElementById('email').value = p.personal_email;
                }

                alertFound.classList.add('found');
                alertNotFound.classList.remove('not-found');

                if (data.has_user) {
                    document.getElementById('textPersonFound').innerText = 
                        `Persona encontrada: ${p.first_name} ${p.first_last_name}. Ya tiene usuario (${data.user.nickname}).`;
                } else {
                    document.getElementById('textPersonFound').innerText = 
                        `Persona encontrada en SICA: ${p.first_name} ${p.first_last_name}. Se vincularán sus datos.`;
                }

            } else {
                alertFound.classList.remove('found');
                alertNotFound.classList.add('not-found');
            }
        } catch (error) {
            console.error(error);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // Editar Usuario
    async function editarUsuario(id) {
        try {
            const response = await fetch(`${baseUsersUrl}/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            if (data.success && data.user) {
                const u = data.user;
                const p = u.person || {};

                document.getElementById('userId').value = u.id;
                document.getElementById('modalTag').innerText = 'EDICIÓN';
                document.getElementById('modalTitle').innerText = 'Editar Usuario';
                document.getElementById('modalSubtitle').innerText = 'Actualiza los datos personales, credenciales o rol del usuario.';
                document.getElementById('btnSubmitText').innerText = 'Actualizar Usuario';

                // Campos
                document.getElementById('document_type').value = p.document_type || 'Cédula de ciudadanía';
                document.getElementById('document_number').value = p.document_number || '';
                document.getElementById('document_number').readOnly = true;
                document.getElementById('btnBuscarPersona').style.display = 'none';

                document.getElementById('first_name').value = p.first_name || '';
                document.getElementById('first_last_name').value = p.first_last_name || '';
                document.getElementById('second_last_name').value = p.second_last_name || '';

                document.getElementById('nickname').value = u.nickname;
                document.getElementById('email').value = u.email;

                // Contraseña opcional en edición
                document.getElementById('password').value = '';
                document.getElementById('password').required = false;
                document.getElementById('labelPassword').innerText = 'CAMBIAR CONTRASEÑA (OPCIONAL)';
                document.getElementById('passwordHint').style.display = 'block';

                // Ocultar alertas de persona
                document.getElementById('alertPersonFound').classList.remove('found');
                document.getElementById('alertPersonNotFound').classList.remove('not-found');

                // Rol
                seleccionarRol(u.role_slug || 'apicola.aprendiz');

                document.getElementById('modalUsuarioBackdrop').classList.add('is-open');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información del usuario.',
                confirmButtonColor: '#059669'
            });
        }
    }

    // Guardar Usuario (Crear o Actualizar)
    async function guardarUsuario(event) {
        event.preventDefault();

        const userId = document.getElementById('userId').value;
        const isEdit = Boolean(userId);
        const url = isEdit ? `${baseUsersUrl}/${userId}` : routeStore;
        const method = isEdit ? 'PUT' : 'POST';

        const btnSubmit = document.getElementById('btnSubmitUsuario');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        btnSubmit.disabled = true;

        const payload = {
            document_type: document.getElementById('document_type').value,
            document_number: document.getElementById('document_number').value,
            first_name: document.getElementById('first_name').value,
            first_last_name: document.getElementById('first_last_name').value,
            second_last_name: document.getElementById('second_last_name').value,
            nickname: document.getElementById('nickname').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            role_slug: document.getElementById('role_slug').value,
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                cerrarModalUsuario();
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: data.message,
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                let errorMsg = data.message || 'Ocurrió un error al procesar el formulario.';
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    errorMsg = data.errors[firstKey][0];
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Verifica los datos',
                    text: errorMsg,
                    confirmButtonColor: '#059669'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo comunicar con el servidor.',
                confirmButtonColor: '#059669'
            });
        } finally {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    }

    // Activar o Desactivar Usuario
    async function toggleEstadoUsuario(id, accion) {
        const confirmResult = await Swal.fire({
            title: accion === 'desactivar' ? '¿Desactivar usuario?' : '¿Activar usuario?',
            text: accion === 'desactivar' 
                ? 'El usuario no podrá iniciar sesión en la plataforma mientras esté inactivo.' 
                : 'El usuario podrá volver a ingresar al módulo Apícola normalmente.',
            icon: accion === 'desactivar' ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: accion === 'desactivar' ? '#ef4444' : '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: accion === 'desactivar' ? 'Sí, desactivar' : 'Sí, activar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmResult.isConfirmed) return;

        try {
            const response = await fetch(`${baseUsersUrl}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Estado actualizado',
                    text: data.message,
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No permitido',
                    text: data.message || 'No fue posible cambiar el estado del usuario.',
                    confirmButtonColor: '#059669'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al intentar cambiar el estado.',
                confirmButtonColor: '#059669'
            });
        }
    }

    // Filtros en tiempo real en la tabla
    function aplicarFiltros() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const roleFilter = document.getElementById('filterRole').value;
        const statusFilter = document.getElementById('filterEstado').value;

        const rows = document.querySelectorAll('#usersTableBody tr[data-id]');

        rows.forEach(row => {
            const textContent = row.textContent.toLowerCase();
            const role = row.getAttribute('data-role');
            const status = row.getAttribute('data-status');

            const matchesQuery = !query || textContent.includes(query);
            const matchesRole = roleFilter === 'all' || role === roleFilter;
            const matchesStatus = statusFilter === 'all' || status === statusFilter;

            if (matchesQuery && matchesRole && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('input', aplicarFiltros);
    document.getElementById('filterRole').addEventListener('change', aplicarFiltros);
    document.getElementById('filterEstado').addEventListener('change', aplicarFiltros);
</script>
@endsection
