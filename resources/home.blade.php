<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Insights — Discover the right career path</title>

    <!-- Fonts: Fraunces (warm serif display) + Plus Jakarta Sans (friendly body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        :root {
            /* Primary brand */
            --bs-primary: #036;
            --bs-primary-rgb: 30, 58, 138;
            --primary-light: #dbe6ff;
            --primary-dark: #036;

            /* Warm accents (sand + terracotta instead of gold — feels human, not corporate) */
            --sand-50: #faf7f2;
            --sand-100: #f3ece0;
            --sand-200: #e8dcc5;
            --ink: #1a1d2e;
            --ink-soft: #4a4f66;
            --ink-muted: #7a8099;
            --accent: #c26a4a;        /* warm terracotta */
            --accent-soft: #fbeee6;

            --radius-card: 20px;
            --radius-button: 999px;
            --radius-input: 14px;

            --shadow-sm: 0 1px 2px rgba(26, 29, 46, 0.04), 0 2px 6px rgba(26, 29, 46, 0.04);
            --shadow-md: 0 4px 12px rgba(26, 29, 46, 0.06), 0 12px 32px rgba(26, 29, 46, 0.06);
            --shadow-lg: 0 20px 60px rgba(30, 58, 138, 0.14);
        }

        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--ink);
            background: var(--sand-50);
            font-weight: 400;
            letter-spacing: -0.005em;
        }

        h1, h2, h3, h4, h5, .display-serif {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .text-ink { color: var(--ink); }
        .text-ink-soft { color: var(--ink-soft); }
        .text-ink-muted { color: var(--ink-muted); }
        .text-accent { color: var(--accent); }
        .bg-sand { background: var(--sand-100); }
        .bg-sand-soft { background: var(--sand-50); }

        /* ---------- NAVBAR ---------- */
        .navbar {
            background: rgba(250, 247, 242, 0.85) !important;
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid rgba(26, 29, 46, 0.06);
            padding: 0;
        }
        .navbar-brand { font-family: 'Fraunces', serif; font-weight: 700; letter-spacing: -0.02em; }
        .navbar-brand .brand-mark {
            width: 38px; height: 38px;
            background: var(--bs-primary);
            border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff;
            font-family: 'Fraunces', serif; font-weight: 700;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
        }
        .brand-logo-img {
            width: 102px;
            height: 90px;
            /* border-radius: 10px; */
            object-fit: contain;
            /* background: #fff; */
            /* border: 1px solid rgba(26, 29, 46, 0.08); */
            /* padding: 3px; */
        }
        .navbar .nav-link {
            color: var(--ink-soft);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 0.9rem !important;
            transition: color 0.2s ease;
        }
        .navbar .nav-link:hover { color: var(--bs-primary); }

        /* ---------- BUTTONS ---------- */
        .btn {
            border-radius: var(--radius-button);
            font-weight: 600;
            padding: 0.72rem 1.5rem;
            font-size: 0.95rem;
            letter-spacing: -0.005em;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            border-width: 1.5px;
        }
        .btn-primary {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.22);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(30, 58, 138, 0.3);
        }
        .btn-outline-primary {
            color: var(--bs-primary);
            border-color: rgba(30, 58, 138, 0.25);
            background: rgba(255, 255, 255, 0.6);
        }
        .btn-outline-primary:hover {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            transform: translateY(-1px);
        }
        .btn-dark {
            background: var(--ink);
            border-color: var(--ink);
        }
        .btn-dark:hover {
            background: #000;
            border-color: #000;
            transform: translateY(-1px);
        }
        .btn-lg { padding: 0.9rem 1.9rem; font-size: 1rem; }

        /* ---------- HERO ---------- */
        .hero {
            position: relative;
            padding: 5rem 25px 6rem;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -20%; right: -10%;
            width: 60%; height: 70%;
            background: radial-gradient(circle, var(--accent-soft) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%; left: -15%;
            width: 50%; height: 60%;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.06) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .hero > .container { position: relative; z-index: 1; }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 0.55rem;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(194, 106, 74, 0.2);
            color: var(--accent);
            font-size: 0.82rem; font-weight: 600;
            padding: 0.45rem 1rem;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }
        .hero-eyebrow .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 3px rgba(194, 106, 74, 0.2);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5.2vw, 4.2rem);
            line-height: 1.02;
            font-weight: 500;
            margin: 1.5rem 0 1.5rem;
        }
        .hero h1 .accent-word {
            font-style: italic;
            font-weight: 400;
            color: var(--bs-primary);
            position: relative;
        }
        .hero h1 .accent-word::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: 0.05em;
            height: 0.12em;
            background: var(--accent);
            opacity: 0.35;
            border-radius: 2px;
        }

        .hero-lead {
            font-size: 1.18rem;
            color: var(--ink-soft);
            line-height: 1.6;
            max-width: 540px;
            margin-bottom: 2rem;
        }

        .search-hero {
            background: #fff;
            border-radius: var(--radius-input);
            padding: 0.45rem;
            box-shadow: var(--shadow-md);
            display: flex; align-items: center;
            max-width: 520px;
            margin-bottom: 2rem;
            border: 1px solid rgba(26, 29, 46, 0.06);
        }
        .search-hero .icon-wrap {
            padding: 0 0.5rem 0 1rem;
            color: var(--ink-muted);
        }
        .search-hero input {
            border: none;
            background: transparent;
            flex: 1;
            padding: 0.8rem 0.4rem;
            font-size: 0.98rem;
            outline: none;
            color: var(--ink);
        }
        .search-hero input::placeholder { color: var(--ink-muted); }
        .search-hero .btn { padding: 0.7rem 1.4rem; }

        .trusted-row {
            display: flex; align-items: center; gap: 1rem;
            margin-top: 2rem;
            color: var(--ink-muted);
            font-size: 0.88rem;
        }
        .avatar-stack { display: flex; }
        .avatar-stack img {
            width: 36px; height: 36px; border-radius: 50%;
            border: 2.5px solid var(--sand-50);
            margin-left: -10px;
            object-fit: cover;
        }
        .avatar-stack img:first-child { margin-left: 0; }

        /* ---------- HERO VISUAL ---------- */
        .hero-visual {
            position: relative;
            aspect-ratio: 4/5;
            max-width: 500px;
            margin: 0 auto;
        }
        .hero-visual .photo-frame {
            position: relative;
            width: 100%; height: 100%;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            background: var(--sand-200);
        }
        .hero-visual .photo-frame img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .hero-visual .photo-frame::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(30, 58, 138, 0.08) 100%);
        }

        /* Floating cards */
        .float-card {
            position: absolute;
            background: #fff;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            box-shadow: 0 12px 40px rgba(26, 29, 46, 0.12);
            border: 1px solid rgba(26, 29, 46, 0.04);
            animation: gentle-float 6s ease-in-out infinite;
        }
        @keyframes gentle-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float-card .fc-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .float-card .fc-label {
            font-size: 0.72rem;
            color: var(--ink-muted);
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .float-card .fc-value {
            font-family: 'Fraunces', serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.1;
        }

        .float-card-1 {
            top: 12%; left: -8%;
            animation-delay: 0s;
        }
        .float-card-1 .fc-icon { background: rgba(30, 58, 138, 0.1); color: var(--bs-primary); }

        .float-card-2 {
            top: 45%; right: -12%;
            animation-delay: 2s;
        }
        .float-card-2 .fc-icon { background: var(--accent-soft); color: var(--accent); }

        .float-card-3 {
            bottom: 8%; left: -5%;
            animation-delay: 4s;
            display: flex; align-items: center; gap: 0.8rem;
        }
        .float-card-3 .rating-stars {
            color: #f0b429;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        /* ---------- SECTION HEADERS ---------- */
        .section-eyebrow {
            display: inline-block;
            color: var(--accent);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.9rem;
        }
        .section-title {
            font-size: clamp(2rem, 3.6vw, 2.8rem);
            font-weight: 500;
            line-height: 1.1;
            margin-bottom: 1rem;
        }
        .section-title em {
            color: var(--bs-primary);
            font-weight: 400;
        }
        .section-lead {
            color: var(--ink-soft);
            font-size: 1.08rem;
            max-width: 620px;
        }

        /* ---------- STATS ROW ---------- */
        .stats-section {
            padding: 3rem 0;
            border-top: 1px solid rgba(26, 29, 46, 0.08);
            border-bottom: 1px solid rgba(26, 29, 46, 0.08);
            background: rgba(255, 255, 255, 0.5);
        }
        .stat-block {
            padding: 0.5rem 0;
        }
        .stat-block .stat-num {
            font-family: 'Fraunces', serif;
            font-size: 3rem;
            font-weight: 600;
            color: var(--bs-primary);
            line-height: 1;
            margin-bottom: 0.4rem;
        }
        .stat-block .stat-lbl {
            color: var(--ink-soft);
            font-size: 0.94rem;
            font-weight: 500;
        }
        .stat-divider {
            width: 1px;
            background: rgba(26, 29, 46, 0.08);
        }

        /* ---------- HOW IT WORKS ---------- */
        .how-section { padding: 6rem 0; }
        .step-card {
            background: #fff;
            border: 1px solid rgba(26, 29, 46, 0.06);
            border-radius: var(--radius-card);
            padding: 2rem 1.8rem;
            height: 100%;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: rgba(30, 58, 138, 0.15);
        }
        .step-number {
            position: absolute;
            top: 1.5rem; right: 1.5rem;
            font-family: 'Fraunces', serif;
            font-size: 3.2rem;
            font-weight: 500;
            font-style: italic;
            color: var(--sand-200);
            line-height: 1;
        }
        .step-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: rgba(30, 58, 138, 0.08);
            color: var(--bs-primary);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.3rem;
        }
        .step-card:nth-child(even) .step-icon {
            background: var(--accent-soft);
            color: var(--accent);
        }
        .step-card h5 {
            font-size: 1.3rem;
            margin-bottom: 0.6rem;
        }
        .step-card p {
            color: var(--ink-soft);
            font-size: 0.96rem;
            margin: 0;
            line-height: 1.55;
        }

        /* ---------- MAJORS ---------- */
        .majors-section { padding: 5rem 0; background: var(--sand-100); }
        .major-card {
            background: #fff;
            border-radius: var(--radius-card);
            padding: 1.6rem;
            height: 100%;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            display: flex; flex-direction: column;
        }
        .major-card:hover {
            transform: translateY(-4px);
            border-color: rgba(30, 58, 138, 0.12);
            box-shadow: var(--shadow-md);
        }
        .major-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--bs-primary);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        .major-card h5 {
            font-size: 1.2rem;
            margin-bottom: 0.4rem;
        }
        .major-card .small {
            color: var(--ink-muted);
            font-size: 0.88rem;
        }
        .major-card .arrow {
            margin-top: auto;
            padding-top: 1.2rem;
            color: var(--ink-muted);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex; align-items: center; gap: 0.4rem;
            transition: color 0.2s ease, gap 0.2s ease;
        }
        .major-card:hover .arrow { color: var(--bs-primary); gap: 0.7rem; }

        /* ---------- JOBS ---------- */
        .jobs-section { padding: 6rem 0; }
        .job-card {
            background: #fff;
            border-radius: var(--radius-card);
            padding: 1.8rem;
            height: 100%;
            border: 1px solid rgba(26, 29, 46, 0.06);
            transition: all 0.3s ease;
            display: flex; flex-direction: column;
        }
        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: rgba(30, 58, 138, 0.15);
        }
        .job-card-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1rem;
        }
        .job-badge {
            background: var(--sand-100);
            color: var(--ink-soft);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
        }
        .job-rating {
            display: flex; align-items: center; gap: 0.25rem;
            color: var(--accent);
            font-weight: 600;
            font-size: 0.9rem;
        }
        .job-card h5 {
            font-size: 1.25rem;
            margin-bottom: 0.4rem;
        }
        .job-card .job-desc {
            color: var(--ink-muted);
            font-size: 0.92rem;
            margin-bottom: 1.5rem;
            flex: 1;
        }
        .job-card .btn {
            width: 100%;
            font-size: 0.92rem;
        }

        /* ---------- PRICING ---------- */
        .pricing-section { padding: 6rem 0; background: var(--sand-100); }
        .pricing-card {
            background: #fff;
            border-radius: var(--radius-card);
            padding: 2.5rem 2rem;
            height: 100%;
            border: 1px solid rgba(26, 29, 46, 0.06);
            position: relative;
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .pricing-card.featured {
            background: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
            box-shadow: var(--shadow-lg);
        }
        .pricing-card.featured h4, .pricing-card.featured .price-amount {
            color: #fff;
        }
        .pricing-card.featured .price-desc { color: rgba(255,255,255,0.75); }
        .pricing-card.featured .feature-item { color: rgba(255,255,255,0.9); }
        .pricing-card.featured .feature-item i { color: #fff; }
        .pricing-card.featured .btn {
            background: #fff;
            color: var(--bs-primary);
            border-color: #fff;
        }
        .pricing-card.featured .btn:hover { background: var(--sand-50); border-color: var(--sand-50); }

        .pricing-tag {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 1rem;
            display: block;
        }
        .pricing-card.featured .pricing-tag { color: rgba(255,255,255,0.8); }
        .pricing-card.featured .pricing-tag .pop {
            background: var(--accent);
            color: #fff;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            margin-left: 0.5rem;
            font-size: 0.7rem;
        }
        .pricing-card h4 {
            font-size: 1.5rem;
            margin-bottom: 0.6rem;
        }
        .price-amount {
            font-family: 'Fraunces', serif;
            font-size: 3.2rem;
            font-weight: 500;
            color: var(--ink);
            line-height: 1;
            margin: 1rem 0 0.3rem;
        }
        .price-amount .currency {
            font-size: 1.1rem;
            color: var(--ink-muted);
            font-weight: 500;
            margin-left: 0.3rem;
        }
        .price-desc {
            color: var(--ink-soft);
            margin-bottom: 1.8rem;
            font-size: 0.96rem;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
        }
        .feature-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.4rem 0;
            color: var(--ink-soft);
            font-size: 0.95rem;
        }
        .feature-item i {
            color: var(--bs-primary);
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* ---------- CTA PANEL ---------- */
        .cta-section { padding: 5rem 0 6rem; }
        .cta-panel {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--primary-dark) 100%);
            border-radius: 32px;
            padding: 4rem 3rem;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .cta-panel::before, .cta-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .cta-panel::before {
            width: 400px; height: 400px;
            top: -200px; left: -100px;
        }
        .cta-panel::after {
            width: 300px; height: 300px;
            bottom: -150px; right: -80px;
        }
        .cta-panel > * { position: relative; z-index: 1; }
        .cta-panel h2 {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .cta-panel h2 em {
            color: var(--accent);
            font-weight: 400;
        }
        .cta-panel p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 560px;
            margin-left: auto; margin-right: auto;
        }

        /* ---------- FOOTER ---------- */
        .site-footer {
            background: var(--ink);
            color: rgba(255,255,255,0.7);
            padding: 3rem 0 2rem;
        }
        .site-footer .brand-mark {
            background: #fff; color: var(--bs-primary);
        }
        .site-footer .brand-logo-img {
            border-color: rgba(255,255,255,0.25);
            background: #fff;
        }
        .site-footer .navbar-brand { color: #fff; }
        .site-footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .site-footer a:hover { color: #fff; }
        .footer-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 2rem 0 1.5rem;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 991px) {
            .hero { padding: 3rem 0 4rem; }
            .hero-visual { margin-top: 3rem; max-width: 380px; }
            .float-card-1 { left: 0; }
            .float-card-2 { right: 0; }
            .float-card-3 { left: 0; }
            .stat-divider { display: none; }
        }
        @media (max-width: 576px) {
            .hero h1 { font-size: 2.2rem; }
            .float-card { padding: 0.7rem 0.9rem; }
            .float-card .fc-icon { width: 32px; height: 32px; font-size: 1rem; }
            .float-card .fc-value { font-size: 1.1rem; }
            .cta-panel { padding: 3rem 1.5rem; }
        }

        /* Entrance animations */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-eyebrow, .hero h1, .hero-lead, .search-hero, .hero-ctas, .trusted-row {
            animation: fade-up 0.8s ease-out backwards;
        }
        .hero-eyebrow { animation-delay: 0.05s; }
        .hero h1 { animation-delay: 0.15s; }
        .hero-lead { animation-delay: 0.25s; }
        .search-hero { animation-delay: 0.35s; }
        .hero-ctas { animation-delay: 0.45s; }
        .trusted-row { animation-delay: 0.55s; }
        .hero-visual { animation: fade-up 1s ease-out 0.3s backwards; }

        /* ---------- CAREER STRIP ---------- */
        .career-strip-section {
            padding: 1.2rem 0 2rem;
            background: transparent;
        }
        .career-strip-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            gap: 0.75rem;
        }
        .career-strip-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--ink-soft);
            margin: 0;
        }
        .career-swiper .swiper-slide {
            width: 340px;
        }
        .career-slide-card {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            height: 230px;
            border: 1px solid rgba(26, 29, 46, 0.08);
            box-shadow: var(--shadow-sm);
            background: #fff;
        }
        .career-slide-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .career-slide-label {
            position: absolute;
            left: 0.5rem;
            bottom: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            background: rgba(15, 42, 95, 0.8);
            padding: 0.28rem 0.52rem;
            border-radius: 999px;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 991px) {
            .career-swiper .swiper-slide { width: 290px; }
            .career-slide-card { height: 190px; }
        }

        @media (max-width: 576px) {
            .career-swiper .swiper-slide { width: 250px; }
            .career-slide-card { height: 165px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
@php
    $isUserLoggedIn = session()->has('user_id');
    $isAdminLoggedIn = session()->has('admin_id');
    $isLoggedIn = $isUserLoggedIn || $isAdminLoggedIn;
    $majorIcons = ['bi-code-slash', 'bi-briefcase', 'bi-gear-wide-connected', 'bi-heart-pulse', 'bi-bar-chart', 'bi-bank'];
    $averageRating = round((float) ($featuredSubMajors->avg('average_rating') ?? 0), 1);
@endphp
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="Career Insights" class="brand-logo-img">
            <span class="fs-5 text-ink">Career Insights</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <i class="bi bi-list fs-3"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
                <li class="nav-item"><a class="nav-link" href="#jobs">Browse jobs</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                @if($isLoggedIn)
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3"><a class="nav-link" href="{{ $isAdminLoggedIn ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="ms-lg-2">
                            @csrf
                            <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-2" href="{{ route('register') }}"><i class="bi bi-arrow-right-short me-1"></i>Get started</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container-fluid">
        <div class="row align-items-center g-1">
            <div class="col-lg-6">
                <span class="hero-eyebrow"><span class="dot"></span>Aligned with Saudi Vision 2030</span>

                <h1>Find the career<br>that actually <span class="accent-word">fits you</span>.</h1>

                <p class="hero-lead">
                    Real insights from people already doing the job. Compare paths, read honest reviews, and choose with confidence.
                </p>

                <form class="search-hero" action="{{ route('home') }}" method="GET">
                    <span class="icon-wrap"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Try software engineer or nurse">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>

                <div class="hero-ctas d-flex flex-wrap gap-2">
                    @if($isLoggedIn)
                        <a href="{{ $isAdminLoggedIn ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-primary btn-lg">
                            Open dashboard <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            Create your account <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                    <a href="#jobs" class="btn btn-outline-primary btn-lg">Browse the catalog</a>
                </div>

                <div class="trusted-row">
                    <div class="avatar-stack">
                        <img src="https://i.pravatar.cc/80?img=12" alt="">
                        <img src="https://i.pravatar.cc/80?img=32" alt="">
                        <img src="https://i.pravatar.cc/80?img=47" alt="">
                        <img src="https://i.pravatar.cc/80?img=25" alt="">
                    </div>
                    <div>Trusted by graduates and professionals across the Kingdom</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="photo-frame">
                        <img src="https://images.unsplash.com/photo-1586724237569-f3d0c1dee8c6?w=900&h=1100&fit=crop" alt="Saudi Arabia career landscape">
                    </div>

                    <div class="float-card float-card-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fc-icon"><i class="bi bi-briefcase-fill"></i></div>
                            <div>
                                <div class="fc-label">Job titles</div>
                                <div class="fc-value">{{ number_format($counts['totalJobTitles']) }}+</div>
                            </div>
                        </div>
                    </div>

                    <div class="float-card float-card-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fc-icon"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="fc-label">Honest reviews</div>
                                <div class="fc-value">{{ number_format($counts['totalEvaluations']) }}+</div>
                            </div>
                        </div>
                    </div>

                    <div class="float-card float-card-3">
                        <div>
                            <div class="fc-label">Avg. rating</div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="fc-value">{{ number_format($averageRating, 1) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CAREER IMAGES STRIP -->
<section class="career-strip-section">
    <div class="container">
        <div class="career-strip-head">
            <p class="career-strip-title">Explore Career Paths Across Industries</p>
        </div>
        <div class="swiper career-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=900&h=600&fit=crop" alt="Software Engineer">
                        <span class="career-slide-label">Software Engineer</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=900&h=600&fit=crop" alt="Business Analyst">
                        <span class="career-slide-label">Business Analyst</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1516549655169-df83a0774514?w=900&h=600&fit=crop" alt="Civil Engineer">
                        <span class="career-slide-label">Civil Engineer</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=900&h=600&fit=crop" alt="Healthcare Specialist">
                        <span class="career-slide-label">Healthcare Specialist</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=900&h=600&fit=crop" alt="Accountant">
                        <span class="career-slide-label">Accountant</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=900&h=600&fit=crop" alt="HR Specialist">
                        <span class="career-slide-label">HR Specialist</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=900&h=600&fit=crop" alt="Marketing Specialist">
                        <span class="career-slide-label">Marketing Specialist</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=900&h=600&fit=crop" alt="Data Analyst">
                        <span class="career-slide-label">Data Analyst</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?w=900&h=600&fit=crop" alt="Teacher">
                        <span class="career-slide-label">Teacher</span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="career-slide-card">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=900&h=600&fit=crop" alt="Finance Manager">
                        <span class="career-slide-label">Finance Manager</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 stat-block">
                <div class="stat-num">{{ number_format($counts['totalJobTitles']) }}+</div>
                <div class="stat-lbl">Career titles mapped</div>
            </div>
            <div class="col-md-4 stat-block border-md-start">
                <div class="stat-num">{{ number_format($counts['totalEvaluations']) }}+</div>
                <div class="stat-lbl">Evaluations from professionals</div>
            </div>
            <div class="col-md-4 stat-block">
                <div class="stat-num">{{ number_format($counts['activeUsers']) }}+</div>
                <div class="stat-lbl">Active users</div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">How it works</span>
            <h2 class="section-title">Four steps from <em>curious</em> to confident</h2>
            <p class="section-lead mx-auto">A straightforward path from exploring options to picking the career that genuinely suits you.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="bi bi-person-plus"></i></div>
                    <h5>Create your account</h5>
                    <p>Sign up in under a minute. Tell us a little about your field of study and interests.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="bi bi-search"></i></div>
                    <h5>Explore job titles</h5>
                    <p>Browse the catalog, filter by major, and preview what each role actually involves day-to-day.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="bi bi-unlock"></i></div>
                    <h5>Unlock the details</h5>
                    <p>Choose a single title or full access and get honest pros and cons from people doing the job.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="bi bi-chat-dots"></i></div>
                    <h5>Ask real professionals</h5>
                    <p>Message employees directly and ask the questions that matter before you decide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED MAJORS -->
<section class="majors-section">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7">
                <span class="section-eyebrow">Featured majors</span>
                <h2 class="section-title">Fields <em>worth exploring</em></h2>
                <p class="section-lead">A selection of popular majors with real sub-titles and evaluations.</p>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <a href="{{ $isLoggedIn ? route('jobs.index') : route('login') }}" class="btn btn-outline-primary">See all majors <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredMajors as $major)
                <div class="col-md-6 col-xl-3">
                    <a href="{{ $isLoggedIn ? route('jobs.index', ['major_id' => $major->major_id]) : route('login') }}" class="text-decoration-none text-reset">
                        <div class="major-card">
                            <div class="major-icon"><i class="bi {{ $majorIcons[$loop->index % count($majorIcons)] }}"></i></div>
                            <h5>{{ $major->major_name }}</h5>
                            <div class="small">{{ number_format($major->sub_majors_count) }} sub-majors</div>
                            <div class="arrow">Explore <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="major-card text-center">
                        <h5 class="mb-2">No majors yet</h5>
                        <div class="small">Major data will appear here after catalog setup.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FEATURED JOBS -->
<section class="jobs-section" id="jobs">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7">
                <span class="section-eyebrow">Featured sub-majors</span>
                <h2 class="section-title">Recently <em>added</em> career titles</h2>
                <p class="section-lead">A glimpse of the newest titles with professional insights already posted.</p>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <a href="{{ $isLoggedIn ? route('jobs.index') : route('login') }}" class="btn btn-outline-primary">View full catalog <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($featuredSubMajors as $job)
                <div class="col-md-6 col-xl-4">
                    <div class="job-card">
                        <div class="job-card-top">
                            <span class="job-badge">{{ $job->major->major_name ?? 'Major' }}</span>
                            <span class="job-rating"><i class="bi bi-star-fill"></i> {{ number_format((float) ($job->average_rating ?? 0), 1) }}</span>
                        </div>
                        <h5>{{ $job->sub_major_name }}</h5>
                        <p class="job-desc">{{ \Illuminate\Support\Str::limit($job->description, 105) }}</p>
                        <a href="{{ $isLoggedIn ? route('jobs.show', $job->sub_major_id) : route('login') }}" class="btn btn-primary">{{ $isLoggedIn ? 'View details' : 'Login to view' }}</a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="job-card text-center">
                        <h5 class="mb-2">No sub-majors yet</h5>
                        <p class="job-desc mb-0">Featured job titles will appear here when available.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="pricing-section" id="pricing">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Pricing</span>
            <h2 class="section-title">Simple plans, <em>real value</em></h2>
            <p class="section-lead mx-auto">Pay once for a single title, or go full access for everything the platform offers.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($pricing as $plan)
                <div class="col-md-6 col-lg-5">
                    <div class="pricing-card {{ $loop->last ? 'featured' : '' }}">
                        <span class="pricing-tag">{{ $loop->last ? 'Full Access' : 'Single Title' }}@if($loop->last) <span class="pop">Most popular</span>@endif</span>
                        <h4>{{ $loop->last ? 'Full monthly access' : 'Pay as you go' }}</h4>
                        <div class="price-amount">{{ $plan['price'] }}<span class="currency">{{ $plan['suffix'] }}</span></div>
                        <p class="price-desc">{{ $plan['description'] }}</p>
                        <ul class="feature-list">
                            @if($loop->last)
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>All career titles unlocked</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>Unlimited insights &amp; evaluations</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>Unlimited messaging</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>Priority support</li>
                            @else
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>One career title of your choice</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>All insights &amp; evaluations</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>Message professionals in that role</li>
                                <li class="feature-item"><i class="bi bi-check-circle-fill"></i>Lifetime access to that title</li>
                            @endif
                        </ul>
                        <a href="{{ $isLoggedIn ? route('subscriptions.index') : route('register') }}" class="btn {{ $loop->last ? '' : 'btn-outline-primary' }} w-100">{{ $loop->last ? 'Get full access' : 'Choose a title' }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-panel">
            <h2>Your next career chapter<br>starts with <em>clarity</em>.</h2>
            <p>Stop choosing careers based on job titles alone. Get the real story from people already living it.</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                @if($isLoggedIn)
                    <a href="{{ $isAdminLoggedIn ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-lg" style="background:#fff;color:var(--bs-primary);">Open dashboard <i class="bi bi-arrow-right ms-1"></i></a>
                    <a href="{{ route('jobs.index') }}" class="btn btn-lg btn-outline-light">Browse catalog</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-lg" style="background:#fff;color:var(--bs-primary);">Create free account <i class="bi bi-arrow-right ms-1"></i></a>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-outline-light">Browse catalog</a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                    <img src="{{ asset('logo.png') }}" alt="Career Insights" class="brand-logo-img">
                    <span class="fs-5" style="color:#fff;">Career Insights</span>
                </a>
                <p class="mt-3 mb-0" style="max-width:380px;">Helping Saudi graduates make career decisions grounded in real experience aligned with Vision 2030.</p>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="text-white mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:0.92rem;">Platform</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ $isLoggedIn ? route('jobs.index') : route('login') }}">Browse jobs</a></li>
                    <li class="mb-2"><a href="#pricing">Pricing</a></li>
                    <li class="mb-2"><a href="#how">How it works</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:0.92rem;">Account</h6>
                <ul class="list-unstyled small">
                    @if($isLoggedIn)
                        <li class="mb-2"><a href="{{ $isAdminLoggedIn ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a></li>
                        <li class="mb-2"><a href="{{ route('profile.index') }}">Profile</a></li>
                    @else
                        <li class="mb-2"><a href="{{ route('login') }}">Log in</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}">Register</a></li>
                    @endif
                    <li class="mb-2"><a href="#">Support</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h6 class="text-white mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:0.92rem;">Legal</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#">Privacy</a></li>
                    <li class="mb-2"><a href="#">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-divider"></div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 small">
            <span>&copy; 2026 Career Insights. All rights reserved.</span>
            <span style="color:rgba(255,255,255,0.5);">Aligned with Saudi Vision 2030</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.career-swiper', {
            loop: true,
            freeMode: true,
            slidesPerView: 'auto',
            spaceBetween: 14,
            allowTouchMove: false,
            speed: 6500,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
        });
    });
</script>
</body>
</html>
