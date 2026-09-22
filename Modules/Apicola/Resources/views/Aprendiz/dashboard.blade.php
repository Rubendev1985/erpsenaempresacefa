@extends('apicola::Aprendiz.layout')

@section('title', 'Panel de Aprendizaje • SENA APÍCOLA')

@section('breadcrumb')
    <span class="active-page">Panel de Aprendizaje</span>
@endsection

@section('styles')
<style>
    /* Estilos del Dashboard de Aprendiz */
    .ap-view-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ap-view-subtitle-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #d97706;
        background: #fef3c7;
        padding: 4px 10px;
        border-radius: 20px;
        margin-bottom: 0.5rem;
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
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ap-user-chip-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
    }

    .ap-user-chip-role {
        font-size: 0.72rem;
        color: #64748b;
    }

    /* Grid de Métricas */
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
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ap-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -3px rgba(15, 23, 42, 0.07);
    }

    .ap-metric-card.border-green { border-top: 3px solid #10b981; }
    .ap-metric-card.border-amber { border-top: 3px solid #f59e0b; }
    .ap-metric-card.border-teal  { border-top: 3px solid #0d9488; }
    .ap-metric-card.border-rose  { border-top: 3px solid #f43f5e; }

    .ap-metric-card-title {
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .ap-metric-card-num {
        font-size: 2.2rem;
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

    /* Banner Formativo */
    .ap-learning-banner {
        background: linear-gradient(135deg, #071e18 0%, #0c2b22 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        border: 1px solid rgba(251, 191, 36, 0.2);
        box-shadow: 0 10px 25px -5px rgba(7, 30, 24, 0.4);
    }

    .ap-learning-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .ap-learning-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.35rem;
    }

    .ap-learning-desc {
        font-size: 0.88rem;
        color: #cbd5e1;
        max-width: 620px;
        line-height: 1.5;
    }

    /* Acciones formativas rápidas */
    .ap-action-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .ap-action-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .ap-action-card:hover {
        border-color: #10b981;
        transform: translateY(-3px);
        box-shadow: 0 10px 24px -4px rgba(16, 185, 129, 0.12);
    }

    .ap-action-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .ap-action-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .ap-action-desc {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
        margin-bottom: 1rem;
    }

    .ap-action-link-text {
        font-size: 0.82rem;
        font-weight: 700;
        color: #059669;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Tabla de Colmenas y Prácticas */
    .ap-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .ap-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .ap-section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .ap-badge-status {
        padding: 3px 9px;
        border-radius: 12px;
        font-size: 0.74rem;
        font-weight: 700;
    }
    .ap-badge-status.activo { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }

    @media (max-width: 992px) {
        .ap-metrics-grid { grid-template-columns: repeat(2, 1fr); }
        .ap-action-cards-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
    <!-- Encabezado con perfil Aprendiz -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag"><i class="fas fa-graduation-cap"></i> MODO FORMATIVO • APRENDIZ</span>
            <h1 class="ap-view-main-title">Panel de Aprendizaje Apícola</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar">
                {{ strtoupper(substr(Auth::user()?->nickname ?? 'AP', 0, 2)) }}
            </div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">{{ Auth::user()?->full_name ?? (Auth::user()?->nickname ?? 'Aprendiz SENA') }}</span>
                <span class="ap-user-chip-role">Rol: Aprendiz Apícola</span>
            </div>
        </div>
    </div>

    <!-- Banner Formativo de Bienvenida -->
    <div class="ap-learning-banner">
        <div>
            <span class="ap-learning-badge"><i class="fas fa-certificate"></i> Centro de Formación La Angostura</span>
            <h2 class="ap-learning-title">Bienvenido a la Práctica de Campo Apícola</h2>
            <p class="ap-learning-desc">
                Como aprendiz del SENA, puedes consultar las colonias de abejas activas, identificar la fenología floral de la región y registrar tus prácticas de inspección sanitaria guiadas por el instructor.
            </p>
        </div>
        <div>
            <a href="{{ route('apicola.admin.inspecciones.index') }}" style="background:#fbbf24; color:#0f172a; font-weight:800; padding:10px 20px; border-radius:10px; text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-size:0.88rem; box-shadow: 0 4px 12px rgba(251,191,36,0.3);">
                <i class="fas fa-clipboard-list"></i> Ver Inspecciones
            </a>
        </div>
    </div>

    <!-- 4 Métricas de Aprendizaje -->
    <div class="ap-metrics-grid">
        <div class="ap-metric-card border-green">
            <div class="ap-metric-card-title">APIARIOS DE PRÁCTICA</div>
            <div class="ap-metric-card-num">{{ $totalApiarios }}</div>
            <div class="ap-metric-card-sub">En Centro La Angostura</div>
        </div>

        <div class="ap-metric-card border-teal">
            <div class="ap-metric-card-title">COLMENAS ACTIVAS</div>
            <div class="ap-metric-card-num">{{ $colmenasActivas }}</div>
            <div class="ap-metric-card-sub">Población en producción</div>
        </div>

        <div class="ap-metric-card border-amber">
            <div class="ap-metric-card-title">EN REVISIÓN / APRENDIZAJE</div>
            <div class="ap-metric-card-num">{{ $colmenasRevision }}</div>
            <div class="ap-metric-card-sub">Casos de estudio clínico</div>
        </div>

        <div class="ap-metric-card border-rose">
            <div class="ap-metric-card-title">INSPECCIONES REGISTRADAS</div>
            <div class="ap-metric-card-num">{{ $totalInspecciones }}</div>
            <div class="ap-metric-card-sub">Historial formativo</div>
        </div>
    </div>

    <!-- Acciones Rápidas del Aprendiz -->
    <div class="ap-action-cards-grid">
        <a href="{{ route('apicola.admin.apiarios.index') }}" class="ap-action-card">
            <div>
                <div class="ap-action-icon" style="background:#ecfdf5; color:#059669;">
                    <i class="fas fa-map-location-dot"></i>
                </div>
                <h3 class="ap-action-title">Georreferenciación</h3>
                <p class="ap-action-desc">Consulta el mapa satelital interactivo de los apiarios en Campoalegre y coordenadas WGS84.</p>
            </div>
            <div class="ap-action-link-text">
                Ver apiarios <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="{{ route('apicola.admin.colmenas.index') }}" class="ap-action-card">
            <div>
                <div class="ap-action-icon" style="background:#fef3c7; color:#d97706;">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <h3 class="ap-action-title">Censo de Colmenas</h3>
                <p class="ap-action-desc">Monitorea los códigos de colmena, fechas de instalación, estado de salud y fechas de última visita.</p>
            </div>
            <div class="ap-action-link-text">
                Explorar colmenas <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="{{ route('apicola.admin.calendario.index') }}" class="ap-action-card">
            <div>
                <div class="ap-action-icon" style="background:#f0fdfa; color:#0d9488;">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3 class="ap-action-title">Calendario Floral</h3>
                <p class="ap-action-desc">Aprende las especies melíferas activas, meses de floración óptima y curva de néctar en la zona.</p>
            </div>
            <div class="ap-action-link-text">
                Ver calendario floral <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Listado de Apiarios Disponibles para el Aprendiz -->
    <div class="ap-section-card">
        <div class="ap-section-header">
            <h3 class="ap-section-title"><i class="fas fa-cubes-stacked" style="color:#10b981;"></i> Apiarios Habilitados para Prácticas</h3>
            <span style="font-size:0.82rem; color:#64748b;">Centro de Formación Agroindustrial La Angostura</span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 10px 14px;">Nombre del Apiario</th>
                        <th style="padding: 10px 14px;">Ubicación GPS</th>
                        <th style="padding: 10px 14px;">Colmenas</th>
                        <th style="padding: 10px 14px;">Estado</th>
                        <th style="padding: 10px 14px; text-align: right;">Acceso</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apiarios as $apiario)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">
                                <i class="fas fa-archive" style="color: #f59e0b; margin-right: 6px;"></i>
                                {{ $apiario->nombre_apiario }}
                            </td>
                            <td style="padding: 12px 14px; font-family: monospace; color: #64748b; font-size: 0.82rem;">
                                {{ number_format($apiario->latitud ?? 2.6857, 4) }}, {{ number_format($apiario->longitud ?? -75.3241, 4) }}
                            </td>
                            <td style="padding: 12px 14px; font-weight: 700; color: #059669;">
                                {{ $apiario->colmenas_count > 0 ? $apiario->colmenas_count : 20 }} colmenas
                            </td>
                            <td style="padding: 12px 14px;">
                                <span class="ap-badge-status activo">{{ $apiario->estado ?? 'Activo' }}</span>
                            </td>
                            <td style="padding: 12px 14px; text-align: right;">
                                <a href="{{ route('apicola.admin.apiarios.index') }}" style="color:#059669; font-weight:700; text-decoration:none; font-size:0.82rem;">
                                    Consultar <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 2rem; color: #94a3b8;">
                                No hay apiarios activos registrados para práctica actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
