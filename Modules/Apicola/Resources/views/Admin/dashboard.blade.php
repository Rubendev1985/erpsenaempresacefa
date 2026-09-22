@extends('apicola::Admin.layout')

@section('title', 'Panel de Administración • SENA APÍCOLA')

@section('breadcrumb')
    <span class="active-page">Panel principal</span>
@endsection

@section('content')
    <!-- Encabezado de la vista con bienvenida -->
    <div class="ap-view-header-row">
        <div>
            <span class="ap-view-subtitle-tag">SISTEMA DE GESTIÓN APÍCOLA</span>
            <h1 class="ap-view-main-title">Panel de Control General</h1>
        </div>
        <div class="ap-user-chip">
            <div class="ap-user-chip-avatar">SB</div>
            <div class="ap-user-chip-info">
                <span class="ap-user-chip-name">Sergio Barrera</span>
                <span class="ap-user-chip-role">Rol: Apicultor</span>
            </div>
        </div>
    </div>

    <!-- 4 Tarjetas de Métricas Ejecutivas -->
    <div class="ap-metrics-grid">
        <!-- Tarjeta 1: Apiarios Activos -->
        <div class="ap-metric-card border-green">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.65rem;">
                <div class="ap-metric-card-title">APIARIOS ACTIVOS</div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, rgba(5, 150, 105, 0.15), rgba(16, 185, 129, 0.08)); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-map-location-dot"></i>
                </div>
            </div>
            <div class="ap-metric-card-num">1</div>
            <div class="ap-metric-card-sub" style="display:flex; align-items:center; gap:6px;">
                <span style="width:7px; height:7px; border-radius:50%; background:#10b981; display:inline-block;"></span>
                Apiario SENA La Angostura
            </div>
        </div>

        <!-- Tarjeta 2: Colmenas en Producción -->
        <div class="ap-metric-card border-teal">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.65rem;">
                <div class="ap-metric-card-title">COLMENAS EN PRODUCCIÓN</div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, rgba(13, 148, 136, 0.15), rgba(45, 212, 191, 0.08)); color: #0d9488; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-cubes-stacked"></i>
                </div>
            </div>
            <div class="ap-metric-card-num">36</div>
            <div class="ap-metric-card-sub" style="display:flex; align-items:center; gap:6px;">
                <span style="width:7px; height:7px; border-radius:50%; background:#0d9488; display:inline-block;"></span>
                De 42 colmenas totales (85%)
            </div>
        </div>

        <!-- Tarjeta 3: Miel Cosechada -->
        <div class="ap-metric-card border-amber">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.65rem;">
                <div class="ap-metric-card-title">MIEL COSECHADA ESTE MES</div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.18), rgba(251, 191, 36, 0.10)); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-jar"></i>
                </div>
            </div>
            <div class="ap-metric-card-num" style="color: #b45309;">22,4 <span style="font-size: 1.25rem; font-weight:700; color:#64748b;">kg</span></div>
            <div class="ap-metric-card-sub" style="display:flex; align-items:center; gap:6px; color:#b45309;">
                <i class="fas fa-arrow-trend-up" style="font-size:0.75rem;"></i>
                En temporada de floración activa
            </div>
        </div>

        <!-- Tarjeta 4: Colmenas en Revisión -->
        <div class="ap-metric-card border-red">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.65rem;">
                <div class="ap-metric-card-title">COLMENAS EN REVISIÓN</div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(248, 113, 113, 0.08)); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-stethoscope"></i>
                </div>
            </div>
            <div class="ap-metric-card-num" style="color: #dc2626;">4</div>
            <div class="ap-metric-card-sub" style="display:flex; align-items:center; gap:6px; color:#e11d48;">
                <i class="fas fa-circle-exclamation" style="font-size:0.75rem;"></i>
                Requieren seguimiento sanitario
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos a Módulos del Sistema -->
    <div class="ap-list-section" style="margin-top: 2.5rem;">
        <div class="ap-list-top-bar" style="margin-bottom: 1.5rem;">
            <h2 class="ap-list-title" style="font-size: 1.4rem; letter-spacing: -0.3px;">Módulos del Sistema Apícola</h2>
            <p class="ap-list-desc">Acceda directamente a las herramientas de control y seguimiento apícola.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.5rem;">
            
            <!-- Tarjeta Módulo 1: Apiarios -->
            <a href="{{ route('apicola.admin.apiarios.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-emerald">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(5, 150, 105, 0.1); color: #047857;">MÓDULO 1</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Gestión de Apiarios</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Control georreferenciado en mapas interactivos de los apiarios, responsables y capacidad de colmenas.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #059669;">
                        <span>Ir a Apiarios</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Módulo 2: Colmenas -->
            <a href="{{ route('apicola.admin.colmenas.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-amber">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(245, 158, 11, 0.12); color: #b45309;">MÓDULO 2</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="fas fa-cubes-stacked"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Gestión de Colmenas</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Listado integral de las 42 colmenas del Apiario La Angostura, estados de producción y fechas de instalación.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #d97706;">
                        <span>Ir a Colmenas</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Módulo 3: Inspección y Registro -->
            <a href="{{ route('apicola.admin.inspecciones.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-sky">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">MÓDULO 3</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Registro e Inspección</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Fichas de visitas técnicas cuadro a cuadro, presencia de reina, cosecha de miel y alertas sanitarias.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #0284c7;">
                        <span>Ir a Inspecciones</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Módulo 4: Calendario Floral -->
            <a href="{{ route('apicola.admin.calendario.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-teal">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(16, 185, 129, 0.1); color: #047857;">MÓDULO 4</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="fas fa-calendar-days"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Calendario Floral</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Seguimiento fenológico de floraciones melíferas y poliníferas de Campoalegre para anticipar cosechas.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #059669;">
                        <span>Ir a Calendario Floral</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Módulo 5: Gestión de Inventario -->
            <a href="{{ route('apicola.admin.inventario.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-purple">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">MÓDULO 5</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                                <i class="fas fa-boxes-packing"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Gestión de Inventario</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Control de herramientas, indumentaria y suministros apícolas, existencias y registro de movimientos.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #0d9488;">
                        <span>Ir a Inventario</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- Tarjeta Módulo 6: Gestión de Usuarios -->
            <a href="{{ route('apicola.admin.usuarios.index') }}" style="text-decoration:none; color:inherit; display:flex;">
                <div class="ap-feature-card card-amber">
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <span class="ap-feature-chip" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">MÓDULO 6</span>
                            <div class="ap-feature-card-icon-box" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="fas fa-users-cog"></i>
                            </div>
                        </div>
                        <h3 style="font-size:1.22rem; font-weight:800; color:#0f172a; margin-bottom:0.5rem;">Gestión de Usuarios</h3>
                        <p style="font-size:0.86rem; color:#64748b; line-height:1.5;">
                            Administración de cuentas de acceso, roles (Administradores y Aprendices), credenciales y vinculación SICA.
                        </p>
                    </div>
                    <div class="ap-feature-btn-link" style="color: #d97706;">
                        <span>Ir a Usuarios</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>

        </div>
    </div>
@endsection