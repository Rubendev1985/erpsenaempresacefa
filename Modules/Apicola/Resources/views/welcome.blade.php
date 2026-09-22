<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Unidad Apícola SENA - Centro de Formación Agroindustrial La Angostura</title>
    <meta name="description" content="Formación, investigación y desarrollo para una apicultura sostenible. Unidad Apícola del SENA - Centro de Formación Agroindustrial La Angostura.">
    <meta name="keywords" content="apicultura, SENA, abejas, miel, formación, apiarios, sostenibilidad">
    <meta name="author" content="SENA - Unidad Apícola">
    <link rel="icon" type="image/png" href="{{ asset('modules/apicola/img/logo.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('apicola-assets/assets/css/welcome.css') }}">

    <style>
        /* Estilos del Detalle Educativo Interactivo */
        .ap-feature-card {
            cursor: pointer;
            position: relative;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .ap-feature-card:hover {
            transform: translateY(-4px);
        }

        /* Estado Activo de la tarjeta seleccionada (Igual a la captura del usuario) */
        .ap-feature-card.active-feature {
            background-color: #e8f5e9 !important;
            border-bottom: 4px solid #2e7d32 !important;
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.18) !important;
        }

        .ap-feature-card.active-feature .ap-feature-icon {
            background-color: #2e7d32 !important;
            color: #ffffff !important;
            transform: scale(1.05);
        }

        .ap-feature-card.active-feature::after {
            content: '';
            position: absolute;
            bottom: -9px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-top: 9px solid #2e7d32;
            z-index: 20;
        }

        /* Indicador / Píldora de sugerencia para hacer clic */
        .ap-features-interactive-hint {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .ap-hint-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: #166534;
            padding: 7px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(26, 92, 42, 0.12);
            border: 1px solid rgba(46, 125, 50, 0.25);
            animation: pulseHint 2.5s infinite;
        }

        @keyframes pulseHint {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(46, 125, 50, 0.2); }
        }

        /* Contenedor del panel de detalle */
        .ap-feature-detail-wrapper {
            max-width: 1300px;
            margin: 1.75rem auto 0;
            display: none;
            animation: apDetailSlideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .ap-feature-detail-wrapper.is-open {
            display: block;
        }

        @keyframes apDetailSlideDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ap-detail-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.25rem 2.5rem;
            box-shadow: 0 20px 40px -10px rgba(26, 92, 42, 0.14), 0 0 0 1px rgba(46, 125, 50, 0.12);
            position: relative;
            overflow: hidden;
        }

        .ap-detail-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1b5e20 0%, #38a61f 50%, #f59e0b 100%);
        }

        .ap-detail-close-btn {
            position: absolute;
            top: 1.25rem;
            right: 1.5rem;
            background: #f1f5f9;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .ap-detail-close-btn:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: rotate(90deg);
        }

        .ap-detail-top-tags {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .ap-badge-pedagogico {
            background: #e8f5e9;
            color: #1b5e20;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
        }

        .ap-topic-badge {
            background: #fef3c7;
            color: #b45309;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .ap-detail-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        .ap-detail-col-left {
            display: flex;
            flex-direction: column;
        }

        .ap-detail-header-flex {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 1.15rem;
        }

        .ap-detail-big-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1b5e20, #38a61f);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 8px 16px rgba(46, 125, 50, 0.25);
            flex-shrink: 0;
        }

        .ap-detail-titles h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
        }

        .ap-detail-titles h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #166534;
            margin-top: 4px;
        }

        .ap-detail-lead {
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.65;
            margin-bottom: 1.25rem;
        }

        .ap-detail-importance-card {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 1.15rem 1.25rem;
            margin-bottom: 1rem;
        }

        .ap-importance-tit {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 6px;
        }

        .ap-importance-text {
            font-size: 0.88rem;
            color: #78350f;
            line-height: 1.55;
            margin: 0;
        }

        .ap-detail-col-right {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ap-right-heading {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.15rem;
        }

        .ap-points-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }

        .ap-point-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .ap-point-card:hover {
            border-color: #86efac;
            background: #f0fdf4;
            transform: translateX(4px);
        }

        .ap-point-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .ap-point-info strong {
            display: block;
            font-size: 0.85rem;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .ap-point-info p {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.4;
            margin: 0;
        }

        .ap-sena-badge-foot {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.82rem;
            color: #166534;
            margin-top: 0.25rem;
        }

        .ap-sena-badge-foot i {
            font-size: 1.25rem;
            color: #15803d;
            flex-shrink: 0;
        }

        @media (max-width: 992px) {
            .ap-detail-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .ap-detail-box {
                padding: 1.5rem;
            }
            .ap-feature-card.active-feature::after {
                display: none;
            }
        }
    </style>
</head>
<body class="apicola-landing">

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="ap-navbar" id="apNavbar">
        <a href="{{ route('apicola.welcome') }}" class="ap-navbar-brand">
            {{-- SENA Logo SVG --}}
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="48" fill="#1a5c2a" stroke="#ffffff" stroke-width="2"/>
                <text x="50" y="42" text-anchor="middle" fill="#ffffff" font-size="16" font-weight="800" font-family="Inter, sans-serif">SENA</text>
                <path d="M30 55 Q50 70 70 55" stroke="#f9a825" stroke-width="3" fill="none" stroke-linecap="round"/>
                <circle cx="50" cy="65" r="3" fill="#f9a825"/>
            </svg>
            <span class="brand-text">
                Unidad Apícola
                <span class="brand-icon">🐝</span>
            </span>
        </a>

        <ul class="ap-nav-links" id="apNavLinks">
            <li><a href="#" class="active">Inicio</a></li>
            <li><a href="#">Nosotros</a></li>
            <li><a href="#">Formación</a></li>
            <li><a href="#apFeatures">Apiario</a></li>
            <li><a href="#">Seguimiento de Miel</a></li>
        </ul>

        <div class="ap-nav-actions">
            @auth
                @if(Auth::user()->roles->contains('slug', 'apicola.aprendiz') && !Auth::user()->roles->contains('slug', 'apicola.admin') && !Auth::user()->hasSuperAdmin())
                    <a href="{{ route('apicola.aprendiz.dashboard') }}" class="ap-btn-login">
                        <i class="fas fa-graduation-cap"></i> Panel Aprendiz
                    </a>
                @else
                    <a href="{{ route('apicola.admin.dashboard') }}" class="ap-btn-login">
                        <i class="fas fa-th-large"></i> Panel Admin
                    </a>
                @endif
            @else
                <a href="{{ route('login', ['redirect' => route('apicola.auth.redirect')]) }}" class="ap-btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </a>
            @endauth

            <div class="ap-lang-selector">
                <i class="fas fa-globe"></i>
                ES
                <i class="fas fa-chevron-down" style="font-size: 0.65rem;"></i>
            </div>
        </div>

        <button class="ap-menu-toggle" id="apMenuToggle" aria-label="Abrir menú">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>

    {{-- ==================== HERO SECTION ==================== --}}
    <section class="ap-hero" id="apHero">
        {{-- Background image --}}
        <div class="ap-hero-bg">
            <img src="{{ asset('apicola-assets/assets/img/hero-apicultor.jpg') }}" alt="Apicultor trabajando en colmena">
        </div>
        <div class="ap-hero-overlay"></div>

        <div class="ap-hero-content">
            {{-- Left: Text content --}}
            <div class="ap-hero-text">
                <div class="ap-hero-badge">
                    <span class="badge-dot"></span>
                    Centro de Formación Agroindustrial La Angostura
                </div>

                <h1 class="ap-hero-title">
                    <span>Unidad Apícola</span>
                    <span>SENA</span>
                </h1>

                <p class="ap-hero-subtitle">
                    <strong>Formación, investigación</strong> y <strong>desarrollo</strong> para<br>
                    una <span class="highlight">apicultura sostenible</span>
                </p>

                <p class="ap-hero-description">
                    En la Unidad Apícola del SENA formamos aprendices y fortaleceremos
                    el conocimiento en el manejo de abejas, la producción de miel y el cuidado
                    del medio ambiente, impulsando el desarrollo del sector apícola en la región.
                </p>

                <div class="ap-hero-buttons">
                    <a href="#" class="ap-btn-primary">
                        🐝 Explorar Módulos
                        <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                    </a>
                    <a href="#" class="ap-btn-outline">
                        <span class="btn-play">▶</span>
                        Conoce nuestra Unidad
                    </a>
                </div>
            </div>

            {{-- Right: Visual --}}
            <div class="ap-hero-visual">
                {{-- Rotating stamp --}}
                <div class="ap-stamp">
                    <div class="ap-stamp-inner">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <path id="circlePath" d="M 100, 100 m -75, 0 a 75,75 0 1,1 150,0 a 75,75 0 1,1 -150,0"/>
                            </defs>
                            <circle cx="100" cy="100" r="90" fill="none" stroke="rgba(249,168,37,0.6)" stroke-width="2" stroke-dasharray="5,5"/>
                            <text fill="#f9a825" font-size="14" font-weight="700" font-family="Inter, sans-serif">
                                <textPath href="#circlePath" startOffset="0%">
                                    ✦ Abejas que generan futuro ✦ Abejas que generan futuro
                                </textPath>
                            </text>
                            <circle cx="100" cy="100" r="6" fill="#f9a825"/>
                        </svg>
                    </div>
                </div>

                {{-- Diamond image --}}
                <div class="ap-diamond-frame">
                    <img src="{{ asset('apicola-assets/assets/img/abejas-panal.jpg') }}" alt="Abejas en panal de miel">
                </div>

                {{-- Hex decorations --}}
                <div class="ap-hex-decoration ap-hex-1">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,3 95,25 95,75 50,97 5,75 5,25" fill="rgba(249,168,37,0.3)" stroke="rgba(249,168,37,0.5)" stroke-width="2"/>
                    </svg>
                </div>
                <div class="ap-hex-decoration ap-hex-2">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,3 95,25 95,75 50,97 5,75 5,25" fill="rgba(249,168,37,0.25)" stroke="rgba(249,168,37,0.4)" stroke-width="2"/>
                    </svg>
                </div>
                <div class="ap-hex-decoration ap-hex-3">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,3 95,25 95,75 50,97 5,75 5,25" fill="rgba(249,168,37,0.2)" stroke="rgba(249,168,37,0.35)" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== FEATURES SECTION ==================== --}}
    <section class="ap-features" id="apFeatures">
        {{-- Interactive Hint --}}
        <div class="ap-features-interactive-hint">
            <span class="ap-hint-pill">
                <i class="fas fa-hand-pointer"></i> Haz clic en cualquiera de las áreas para conocer qué es y su importancia
            </span>
        </div>

        <div class="ap-features-grid">
            {{-- Feature 1: Apiario --}}
            <div class="ap-feature-card" onclick="selectFeature('apiarios')" data-feature="apiarios" title="Clic para ver información del Apiario SENA">
                <div class="ap-feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Apiario</h3>
                <p>Manejo y control técnico de 20 colmenas a cargo del apicultor Sergio Barrera.</p>
            </div>

            {{-- Feature 2: Producción de Miel --}}
            <div class="ap-feature-card" onclick="selectFeature('miel')" data-feature="miel" title="Clic para ver información de Producción de Miel">
                <div class="ap-feature-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Producción de Miel</h3>
                <p>Seguimiento, cosecha y trazabilidad del producto.</p>
            </div>

            {{-- Feature 3: Colmenas --}}
            <div class="ap-feature-card" onclick="selectFeature('colmenas')" data-feature="colmenas" title="Clic para ver información de Colmenas">
                <div class="ap-feature-icon">
                    <i class="fas fa-hive"></i>
                </div>
                <h3>Colmenas</h3>
                <p>Registro, control y seguimiento de las 20 colmenas activas.</p>
            </div>

            {{-- Feature 4: Calendario floral --}}
            <div class="ap-feature-card" onclick="selectFeature('floral')" data-feature="floral" title="Clic para ver información de Calendario floral">
                <div class="ap-feature-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Calendario floral</h3>
                <p>Registro de floraciones y disponibilidad de alimento.</p>
            </div>

            {{-- Feature 5: Inventario --}}
            <div class="ap-feature-card" onclick="selectFeature('inventario')" data-feature="inventario" title="Clic para ver información de Inventario">
                <div class="ap-feature-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Inventario</h3>
                <p>Registro, control y seguimiento de los productos apícolas.</p>
            </div>

            {{-- Stamp CTA --}}
            <div class="ap-features-stamp">
                <div class="ap-stamp-text">
                    La apicultura<br>
                    también es<br>
                    <span class="stamp-highlight">educación</span>
                </div>
            </div>
        </div>

        {{-- ==================== DETALLE EDUCATIVO INTERACTIVO ==================== --}}
        <div class="ap-feature-detail-wrapper" id="apFeatureDetailWrapper">
            <div class="ap-detail-box">
                <!-- Botón cerrar -->
                <button class="ap-detail-close-btn" onclick="closeFeatureDetail()" title="Cerrar detalle">
                    <i class="fas fa-times"></i>
                </button>

                <!-- Etiquetas superiores -->
                <div class="ap-detail-top-tags">
                    <span class="ap-badge-pedagogico">
                        <i class="fas fa-certificate"></i> Formación Apícola SENA
                    </span>
                    <span class="ap-topic-badge" id="apDetailBadge">
                        <i class="fas fa-info-circle"></i> Módulo Pedagógico
                    </span>
                </div>

                <!-- Contenido Principal en 2 Columnas -->
                <div class="ap-detail-grid">
                    <!-- Columna Izquierda: Introducción e Importancia -->
                    <div class="ap-detail-col-left">
                        <div class="ap-detail-header-flex">
                            <div class="ap-detail-big-icon" id="apDetailIconBox">
                                <i class="fas fa-map-marked-alt" id="apDetailBigIcon"></i>
                            </div>
                            <div class="ap-detail-titles">
                                <h2 id="apDetailTitle">¿Qué es el Apiario SENA?</h2>
                                <h4 id="apDetailSubtitle">1 apiario tecnificado que alberga más de 20 colmenas productivas</h4>
                            </div>
                        </div>

                        <p class="ap-detail-lead" id="apDetailLead">
                            Un apiario (o colmenar) es el espacio geográfico debidamente seleccionado y acondicionado donde se ubica el conjunto de colmenas para la cría, manejo y producción de abejas melíferas (<em>Apis mellifera</em>). En el Centro La Angostura disponemos de <strong>1 único apiario centralizado con más de 20 colmenas tecnificadas</strong>.
                        </p>

                        <!-- Tarjeta Destacada: ¿Por qué es importante? -->
                        <div class="ap-detail-importance-card">
                            <div class="ap-importance-tit">
                                <i class="fas fa-star" style="color: #f59e0b;"></i>
                                <span>¿Por qué es fundamental en la apicultura?</span>
                            </div>
                            <p class="ap-importance-text" id="apDetailImportance">
                                Un apiario bien planificado garantiza que las abejas trabajen sin estrés térmico, previene el pillaje entre colmenas, optimiza las rutas de vuelo para la recolección de néctar y propóleos, y resguarda la seguridad de la comunidad al respetar distancias mínimas reglamentarias (> 200m).
                            </p>
                        </div>
                    </div>

                    <!-- Columna Derecha: Puntos Clave y Enfoque SENA -->
                    <div class="ap-detail-col-right">
                        <div class="ap-right-heading">
                            <i class="fas fa-clipboard-check" style="color: #2e7d32;"></i>
                            <span>Aspectos Clave y Buenas Prácticas</span>
                        </div>

                        <!-- Lista de puntos inyectados por JS -->
                        <div class="ap-points-list" id="apPointsList">
                            <!-- Inyectado dinámicamente -->
                        </div>

                        <!-- Nota del Centro La Angostura -->
                        <div class="ap-sena-badge-foot">
                            <i class="fas fa-graduation-cap"></i>
                            <div>
                                <strong>Enfoque SENA La Angostura:</strong>
                                <span id="apDetailSenaNote">En el Centro La Angostura contamos con 1 apiario principal con más de 20 colmenas activas, donde los aprendices realizan prácticas reales de georreferenciación y manejo productivo.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== SCRIPTS ==================== --}}
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('apNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const menuToggle = document.getElementById('apMenuToggle');
        const navLinks = document.getElementById('apNavLinks');
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
            menuToggle.classList.toggle('active');
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.ap-feature-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Datos pedagógicos completos para las 5 áreas apícolas
        const apFeaturesData = {
            apiarios: {
                badge: "Apiario Central • SENA La Angostura",
                icon: "fa-map-marked-alt",
                title: "¿Qué es el Apiario SENA?",
                subtitle: "1 único apiario tecnificado con 20 colmenas a cargo de Sergio Barrera",
                lead: "Un apiario (o colmenar) es el espacio físico debidamente acondicionado donde se ubica y maneja el conjunto de colmenas de abejas melíferas (<em>Apis mellifera</em>). En el Centro de Formación Agroindustrial La Angostura disponemos de <strong>un único apiario centralizado</strong>, diseñado bajo rigurosos parámetros técnicos y de bioseguridad, el cual alberga actualmente <strong>20 colmenas tecnificadas</strong> a cargo del apicultor <strong>Sergio Barrera</strong>.",
                importance: "Concentrar las 20 colmenas en un apiario técnicamente planificado permite estandarizar las inspecciones sanitarias, garantizar una distancia de bioseguridad reglamentaria (> 200 m de caminos y corrales), optimizar las fuentes de néctar del centro y amortiguar el calor del Huila con microclima adecuado.",
                senaNote: "En el Centro La Angostura contamos con 1 único apiario con 20 colmenas activas a cargo del apicultor Sergio Barrera, donde los aprendices realizan prácticas reales de manejo técnico, multiplicación, nutrición y cosecha de miel.",
                points: [
                    { icon: "fa-cubes-stacked", title: "20 Colmenas Tecnificadas", desc: "Población de 20 colmenas tipo Langstroth sobre bases individuales con trampas antihormigas a cargo de Sergio Barrera." },
                    { icon: "fa-compass", title: "Orientación y Piqueras", desc: "Orientadas al Este para aprovechar la luz matutina e impulsar las salidas tempranas de pecoreo." },
                    { icon: "fa-droplet", title: "Fuentes de Agua Limpia", desc: "Bebederos limpios e hidratación permanente para que las abejas termorregulen el nido a 35°C." },
                    { icon: "fa-shield-halved", title: "Bioseguridad y Barreras", desc: "Aislamiento superior a 200 metros y barreras vegetales vivas que obligan a las abejas a ganar altura." }
                ]
            },
            miel: {
                badge: "Cosecha & Trazabilidad de Miel",
                icon: "fa-flask",
                title: "¿Cómo se produce y cosecha la Miel?",
                subtitle: "De la flor al panal y del panal a la mesa con inocuidad",
                lead: "La miel es la sustancia dulce natural que las abejas producen a partir del néctar de las flores. Las abejas lo transforman combinándolo con enzimas específicas de su organismo, lo deshidratan en los alvéolos del panal mediante corrientes de aire generadas por su aleteo y lo sellan con cera pura (operculado) al alcanzar su madurez.",
                importance: "Es un alimento energético extraordinario, rico en bioflavonoides, enzimas activas y agentes bactericidas naturales. Una cosecha higiénica bajo Buenas Prácticas de Manufactura asegura su inocuidad y mantiene intactas sus propiedades nutricionales sin necesidad de calentamiento.",
                senaNote: "En la sala de extracción de La Angostura, los aprendices emplean centrífugas radiales en acero inoxidable y refractómetros digitales para certificar miel virgen sin adulteración.",
                points: [
                    { icon: "fa-check-double", title: "Operculado Maduro (>80%)", desc: "Se cosechan únicamente panales donde la mayor parte de las celdas ya han sido selladas con cera por las abejas." },
                    { icon: "fa-gauge-high", title: "Humedad Menor al 18.5%", desc: "Medición rigurosa con refractómetro para garantizar que la miel no fermente por acción de levaduras." },
                    { icon: "fa-gears", title: "Extracción en Frío", desc: "Centrifugado mecánico en frío que extrae la miel sin romper los panales de cera, permitiendo su reutilización." },
                    { icon: "fa-certificate", title: "Trazabilidad de Origen", desc: "Registro del apiario, fecha de castra y floración botánica predominante para garantizar pureza total." }
                ]
            },
            colmenas: {
                badge: "Manejo Técnico de Colmenas",
                icon: "fa-hive",
                title: "¿Cómo se manejan las Colmenas en el SENA?",
                subtitle: "Monitoreo integral de más de 20 colmenas tecnificadas",
                lead: "La colmena tecnificada (modelo Langstroth) es una estructura modular de cuadros móviles que respeta el 'espacio abeja' (6 a 9 mm). En nuestro apiario del SENA, cada una de las <strong>más de 20 colmenas</strong> alberga poblaciones que superan los 50.000 individuos perfectamente jerarquizados: la reina fértil, miles de obreras y los zánganos.",
                importance: "El seguimiento cuadro por cuadro a cada una de las más de 20 colmenas permite monitorear el vigor reproductivo de la reina, calcular las reservas de miel y polen, planificar la alimentación de sostén y mantener la sanidad del colmenar.",
                senaNote: "Con más de 20 colmenas activas en el apiario del SENA, los aprendices aprenden lectura de cuadros, monitoreo sanitario y registros digitales de producción.",
                points: [
                    { icon: "fa-crown", title: "Reina y Vigor Reproductivo", desc: "Reinas fecundadas de alta calidad genética que aseguran postura uniforme y colonias dóciles." },
                    { icon: "fa-layer-group", title: "Cámara de Cría vs Alzas", desc: "Separación clara entre la cámara inferior para reproducción y las alzas superiores para miel pura." },
                    { icon: "fa-users-gear", title: "Población y Cosecha", desc: "Monitoreo constante del volumen de abejas pecoreadoras para sincronizar la cosecha con las floraciones." },
                    { icon: "fa-stethoscope", title: "Sanidad y Control de Varroa", desc: "Muestreos periódicos para verificar niveles de infestación y aplicar manejos zootécnicos oportunos." }
                ]
            },
            floral: {
                badge: "Fenología & Nutrición Apícola",
                icon: "fa-seedling",
                title: "¿Qué es el Calendario Floral?",
                subtitle: "La brújula botánica para sincronizar el apiario con la floración",
                lead: "El calendario floral es el registro sistemático y cronológico de los periodos de floración de las especies vegetales melíferas (productoras de néctar) y poliníferas (productoras de polen) presentes en el radio de pecoreo de las colmenas (zona de Campoalegre y cuenca del río Neiva).",
                importance: "Es la herramienta más estratégica del apicultor: permite preparar las colmenas semanas antes del gran flujo floral para que coincidan con la máxima población de pecoreadoras y colocar las alzas melarias a tiempo, evitando la pérdida de cosechas.",
                senaNote: "En La Angostura investigamos y caracterizamos la fenología del Matarratón, Guácimo, Cují, Café y Cítricos para optimizar el calendario productivo regional.",
                points: [
                    { icon: "fa-calendar-week", title: "Curva de Floración Anual", desc: "Diferenciación entre floraciones principales de mielada y floraciones de sostén en periodos lluviosos." },
                    { icon: "fa-spa", title: "Néctar vs Polen", desc: "Identificación de especies que impulsan el desarrollo de la cría (polen) y las que llenan las alzas (néctar)." },
                    { icon: "fa-bowl-food", title: "Alimentación Estratégica", desc: "Planificación de alimentación de sostén (jarabes y tortas proteicas) en épocas críticas para evitar la fuga de enjambres." },
                    { icon: "fa-leaf", title: "Conservación de Cercas Vivas", desc: "Fomento de siembra y protección de especies melíferas nativas para garantizar alimento permanente." }
                ]
            },
            inventario: {
                badge: "Bioseguridad & Herramientas",
                icon: "fa-box",
                title: "¿Por qué es crucial el Inventario en Apicultura?",
                subtitle: "Equipos de protección, herramientas e insumos al servicio de la seguridad",
                lead: "El inventario apícola comprende el control, mantenimiento preventivo y desinfección de los equipos de protección personal (overoles, caretas, guantes), herramientas de forja (ahumadores, espátulas) y material biológico y de madera (cámaras, alzas, marcos y cera).",
                importance: "El trabajo con abejas defensivas exige que ningún equipo falle en campo: un velo roto o un ahumador que se apague pone en riesgo al operario. Además, el control riguroso de herramientas evita la transmisión cruzada de patógenos entre apiarios.",
                senaNote: "En el taller y bodega apícola del SENA, los aprendices fabrican alzas, arman y alambran marcos, estampan cera pura de opérculo y desinfectan herramientas.",
                points: [
                    { icon: "fa-vest", title: "Indumentaria Certificada", desc: "Overoles de dril o telas ventiladas de color blanco, caretas con malla metálica fija y guantes flexibles." },
                    { icon: "fa-smog", title: "Ahumador Técnico", desc: "Genera humo blanco y frío con viruta o eucalipto, bloqueando las feromonas de alarma de las abejas." },
                    { icon: "fa-toolbox", title: "Espátula o Palanca", desc: "Herramienta de acero inoxidable para despegar marcos fijados con propóleo sin causar sacudidas violentas." },
                    { icon: "fa-pump-soap", title: "Desinfección de Material", desc: "Flameado o desinfección de herramientas antes de pasar de una colmena a otra para prevenir enfermedades." }
                ]
            }
        };

        function selectFeature(featureKey) {
            const data = apFeaturesData[featureKey];
            if (!data) return;

            // Marcar tarjeta activa con el estilo verde claro
            document.querySelectorAll('.ap-feature-card').forEach(card => {
                if (card.getAttribute('data-feature') === featureKey) {
                    card.classList.add('active-feature');
                } else {
                    card.classList.remove('active-feature');
                }
            });

            // Actualizar textos e íconos en el panel de detalle
            document.getElementById('apDetailBadge').innerHTML = `<i class="fas fa-info-circle"></i> ${data.badge}`;
            document.getElementById('apDetailBigIcon').className = `fas ${data.icon}`;
            document.getElementById('apDetailTitle').textContent = data.title;
            document.getElementById('apDetailSubtitle').textContent = data.subtitle;
            document.getElementById('apDetailLead').innerHTML = data.lead;
            document.getElementById('apDetailImportance').innerHTML = data.importance;
            document.getElementById('apDetailSenaNote').innerHTML = data.senaNote;

            // Inyectar los 4 puntos clave
            const pointsList = document.getElementById('apPointsList');
            pointsList.innerHTML = '';
            data.points.forEach(pt => {
                const ptCard = document.createElement('div');
                ptCard.className = 'ap-point-card';
                ptCard.innerHTML = `
                    <div class="ap-point-icon"><i class="fas ${pt.icon}"></i></div>
                    <div class="ap-point-info">
                        <strong>${pt.title}</strong>
                        <p>${pt.desc}</p>
                    </div>
                `;
                pointsList.appendChild(ptCard);
            });

            // Mostrar el contenedor con animación
            const detailWrapper = document.getElementById('apFeatureDetailWrapper');
            detailWrapper.classList.remove('is-open');
            void detailWrapper.offsetWidth; // Reflow
            detailWrapper.classList.add('is-open');

            // Desplazamiento suave al panel de detalle
            setTimeout(() => {
                const yOffset = -70;
                const y = detailWrapper.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }, 60);
        }

        function closeFeatureDetail() {
            const detailWrapper = document.getElementById('apFeatureDetailWrapper');
            detailWrapper.classList.remove('is-open');
            document.querySelectorAll('.ap-feature-card').forEach(card => {
                card.classList.remove('active-feature');
            });
        }
    </script>
</body>
</html>