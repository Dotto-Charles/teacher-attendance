<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        EduAttend | Mfumo wa Mahudhurio
    </title>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>

        :root{
            --primary:#005bac;
            --primary2:#0d6efd;
            --secondary:#0b2239;
            --gold:#f4b400;
            --white:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --bg:#f1f5f9;
            --border:#dbe4ef;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--text);
            overflow-x:hidden;
            position:relative;
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        /* ================= WATERMARK ================= */

        .watermark{
            position:fixed;
            inset:0;
            background:
                url('{{ asset('images/ppppppp.jpg') }}')
                center center no-repeat;

            background-size:450px;

            opacity:.04;

            pointer-events:none;

            z-index:0;
        }

        /* ================= TOPBAR ================= */

        .topbar{
            background:
                linear-gradient(
                90deg,
                var(--secondary),
                var(--primary)
                );

            color:white;

            padding:14px 20px;

            position:relative;

            z-index:10;

            box-shadow:
                0 2px 10px rgba(0,0,0,.08);
        }

        .topbar-inner{
            max-width:1250px;
            margin:auto;

            display:flex;
            align-items:center;
            justify-content:center;
            gap:15px;

            text-align:center;
        }

        .topbar img{
            width:60px;
            height:60px;
            border-radius:50%;
            background:white;
            object-fit:cover;
            padding:3px;
        }

        .topbar h2{
            margin:0;
            font-size:24px;
            font-weight:800;
            line-height:1.2;
        }

        .topbar p{
            margin:0;
            font-size:13px;
            opacity:.9;
        }

        /* ================= NAVBAR ================= */

        .navbar-custom{
            background:white;
            position:sticky;
            top:0;
            z-index:99;
            border-bottom:1px solid #e2e8f0;
        }

        .nav-inner{
            max-width:1250px;
            margin:auto;

            padding:16px 22px;

            display:flex;
            align-items:center;
            justify-content:space-between;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .brand img{
            width:52px;
            height:52px;
            border-radius:50%;
            object-fit:cover;
            background:white;
            border:2px solid #dbeafe;
        }

        .brand-text h3{
            margin:0;
            font-size:20px;
            font-weight:800;
            color:var(--secondary);
        }

        .brand-text span{
            font-size:12px;
            color:var(--muted);
        }

        .nav-links{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .nav-link{
            color:#334155;
            font-size:14px;
            font-weight:600;
            padding:10px 14px;
            border-radius:10px;
            transition:.3s;
        }

        .nav-link:hover{
            background:#f1f5f9;
            color:var(--primary);
        }

        /* ================= BUTTONS ================= */

        .btn-custom{
            padding:12px 22px;
            border-radius:12px;
            font-size:14px;
            font-weight:700;
            transition:.3s;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }

        .btn-login{
            background:#eff6ff;
            color:var(--primary);
            border:1px solid #bfdbfe;
        }

        .btn-login:hover{
            background:#dbeafe;
            color:var(--primary);
        }

        .btn-primary-custom{
            background:
                linear-gradient(
                135deg,
                var(--primary),
                var(--primary2)
                );

            color:white;

            border:none;

            box-shadow:
                0 10px 20px rgba(0,91,172,.15);
        }

        .btn-primary-custom:hover{
            transform:translateY(-2px);
            color:white;
        }

        /* ================= HERO ================= */

        .hero{
            position:relative;
            z-index:2;

            padding:
                90px 20px 100px;
        }

        .hero-container{
            max-width:1250px;
            margin:auto;

            display:grid;
            grid-template-columns:1.1fr .9fr;
            gap:40px;
            align-items:center;
        }

        .hero-badge{
            display:inline-flex;
            align-items:center;
            gap:10px;

            padding:10px 18px;

            background:#dbeafe;

            color:#1e40af;

            border-radius:999px;

            font-size:13px;
            font-weight:700;

            margin-bottom:24px;
        }

        .dot{
            width:10px;
            height:10px;
            border-radius:50%;
            background:#22c55e;
        }

        .hero h1{
            font-size:clamp(40px,7vw,72px);
            line-height:1.05;
            margin-bottom:24px;
            color:var(--secondary);
            font-weight:800;
        }

        .highlight{
            color:var(--primary);
        }

        .hero p{
            font-size:18px;
            line-height:1.9;
            color:var(--muted);
            margin-bottom:35px;
            max-width:700px;
        }

        .hero-buttons{
            display:flex;
            gap:16px;
            flex-wrap:wrap;
        }

        /* ================= HERO CARD ================= */

        .hero-card{
            background:white;

            border-radius:28px;

            padding:35px;

            border:1px solid #e2e8f0;

            box-shadow:
                0 20px 40px rgba(15,23,42,.06);
        }

        .hero-card img{
            width:100%;
            border-radius:20px;
            object-fit:cover;
        }

        .hero-card-content{
            margin-top:24px;
        }

        .hero-card-content h4{
            font-weight:800;
            color:var(--secondary);
            margin-bottom:14px;
        }

        .hero-card-content p{
            margin:0;
            color:var(--muted);
            font-size:15px;
            line-height:1.8;
        }

        /* ================= FEATURES ================= */

        .features{
            padding:0 20px 100px;
            position:relative;
            z-index:2;
        }

        .section-title{
            text-align:center;
            margin-bottom:55px;
        }

        .section-title h2{
            font-size:42px;
            font-weight:800;
            color:var(--secondary);
            margin-bottom:15px;
        }

        .section-title p{
            color:var(--muted);
            max-width:700px;
            margin:auto;
            line-height:1.8;
        }

        .features-grid{
            max-width:1200px;
            margin:auto;

            display:grid;
            grid-template-columns:
                repeat(auto-fit,minmax(280px,1fr));

            gap:25px;
        }

        .feature-card{
            background:white;
            border-radius:24px;
            padding:35px 30px;
            border:1px solid #e2e8f0;
            transition:.3s;

            box-shadow:
                0 10px 30px rgba(15,23,42,.04);
        }

        .feature-card:hover{
            transform:translateY(-8px);
        }

        .feature-icon{
            width:70px;
            height:70px;
            border-radius:18px;

            display:flex;
            align-items:center;
            justify-content:center;

            background:#eff6ff;

            color:var(--primary);

            font-size:30px;

            margin-bottom:22px;
        }

        .feature-card h3{
            font-size:22px;
            color:var(--secondary);
            margin-bottom:14px;
            font-weight:700;
        }

        .feature-card p{
            color:var(--muted);
            line-height:1.8;
            font-size:15px;
        }

        /* ================= ABOUT ================= */

        .about{
            padding:0 20px 100px;
            position:relative;
            z-index:2;
        }

        .about-card{
            max-width:1200px;
            margin:auto;

            background:white;

            border-radius:30px;

            padding:60px 45px;

            border:1px solid #e2e8f0;

            box-shadow:
                0 15px 40px rgba(15,23,42,.05);
        }

        .about-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:40px;
            align-items:center;
        }

        .about img{
            width:100%;
            border-radius:22px;
        }

        .about-content h2{
            font-size:42px;
            font-weight:800;
            color:var(--secondary);
            margin-bottom:20px;
        }

        .about-content p{
            color:var(--muted);
            line-height:1.9;
            margin-bottom:20px;
        }

        .about-list{
            display:flex;
            flex-direction:column;
            gap:15px;
        }

        .about-item{
            display:flex;
            align-items:center;
            gap:12px;
            font-weight:600;
            color:#334155;
        }

        .about-item i{
            color:#16a34a;
            font-size:18px;
        }

        /* ================= FOOTER ================= */

        footer{
            background:var(--secondary);
            color:white;
            padding:40px 20px;
            text-align:center;
            position:relative;
            z-index:2;
        }

        footer p{
            margin:0;
            opacity:.9;
            line-height:1.8;
        }

        /* ================= MOBILE ================= */

        @media(max-width:992px){

            .hero-container,
            .about-grid{
                grid-template-columns:1fr;
            }

            .hero{
                padding-top:70px;
            }

            .hero-card{
                order:-1;
            }
        }

        @media(max-width:768px){

            .topbar-inner,
            .nav-inner{
                flex-direction:column;
                gap:16px;
                text-align:center;
            }

            .hero-buttons{
                flex-direction:column;
            }

            .btn-custom{
                width:100%;
            }

            .hero h1{
                font-size:42px;
            }

            .hero p{
                font-size:16px;
            }

            .section-title h2,
            .about-content h2{
                font-size:32px;
            }

            .about-card{
                padding:35px 22px;
            }

            .hero-card{
                padding:22px;
            }

            .watermark{
                background-size:260px;
            }
        }

    </style>

</head>

<body>

    <!-- WATERMARK -->
    <div class="watermark"></div>

    <!-- TOPBAR -->
    <div class="topbar">

        <div class="topbar-inner">

            <img src="{{ asset('images/ppppppp.jpg') }}"
                 alt="Logo">

            <div>
                <h2>
                    Chemba District Council
                </h2>

                <p>
                    Mfumo wa Usimamizi wa Mahudhurio ya Walimu
                </p>
            </div>

        </div>

    </div>

    <!-- NAVBAR -->
    <nav class="navbar-custom">

        <div class="nav-inner">

            <div class="brand">

                <img src="{{ asset('images/ppppppp.jpg') }}"
                     alt="Logo">

                <div class="brand-text">

                    <h3>EduAttend</h3>

                    <span>
                        Attendance Management System
                    </span>

                </div>

            </div>

            <div class="nav-links">

            <a href="{{ url('/about-developer') }}" class="nav-link">
                         Kuhusu Mtengenezaji
              </a>

                <a href="#features"
                   class="nav-link">

                    Huduma

                </a>

                @guest

                    <a href="{{ route('login') }}"
                       class="btn-custom btn-login">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Ingia

                    </a>

                    <a href="{{ route('register') }}"
                       class="btn-custom btn-primary-custom">

                        <i class="bi bi-person-plus"></i>

                        Jiandikishe

                    </a>

                @else

                    <a href="{{ route('dashboard') }}"
                       class="btn-custom btn-primary-custom">

                        Dashboard

                    </a>

                @endguest

            </div>

        </div>

    </nav>

    <!-- HERO -->
    <section class="hero">

        <div class="hero-container">

            <!-- LEFT -->
            <div>

                <div class="hero-badge">

                    <div class="dot"></div>

                    Mfumo wa Mahudhurio 

                </div>

                <h1>

                    Mfumo wa Kisasa wa

                    <span class="highlight">
                        Mahudhurio ya Walimu
                    </span>

                </h1>

                <p>

                    Mfumo rasmi wa Chemba District Council unaorahisisha
                    usimamizi wa mahudhurio ya walimu kwa kutumia GPS,
                    taarifa za muda halisi na ripoti za kisasa kwa viongozi
                    wa elimu na wasimamizi wa shule.

                </p>

                <div class="hero-buttons">

                    @guest

                        <a href="{{ route('login') }}"
                           class="btn-custom btn-primary-custom">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Ingia Mfumo

                        </a>

                        <a href="{{ route('register') }}"
                           class="btn-custom btn-login">

                            <i class="bi bi-person-plus"></i>

                            Fungua Akaunti

                        </a>

                    @else

                        <a href="{{ route('dashboard') }}"
                           class="btn-custom btn-primary-custom">

                            Dashboard

                        </a>

                    @endguest

                </div>

            </div>

            <!-- RIGHT -->
            <div class="hero-card">

                <img src="{{ asset('images/ppppppp.jpg') }}"
                     alt="System">

                <div class="hero-card-content">

                    <h4>
                        Mfumo wa Mahudhurio
                    </h4>

                    <p>

                        Mfumo huu umeundwa kwa ajili ya kuboresha usimamizi
                        wa mahudhurio ya walimu katika shule zote za
                        halmashauri kwa usalama, uwazi na ufanisi zaidi.

                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="features"
             id="features">

        <div class="section-title">

            <h2>
                Huduma za Mfumo
            </h2>

            <p>

                Mfumo wa EduAttend umeboreshwa kwa teknolojia za kisasa
                ili kusaidia usimamizi wa elimu kwa ufanisi mkubwa.

            </p>

        </div>

        <div class="features-grid">

            <div class="feature-card">

                <div class="feature-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <h3>GPS Tracking</h3>

                <p>
                    Hakiki mahudhurio ya walimu kwa kutumia eneo halisi
                    la shule kwa usahihi mkubwa.
                </p>

            </div>

            <div class="feature-card">

                <div class="feature-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <h3>Ripoti za Muda Halisi</h3>

                <p>
                    Pata taarifa za mahudhurio kwa muda halisi kutoka
                    shule zote ndani ya halmashauri.
                </p>

            </div>

            <div class="feature-card">

                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <h3>Usalama wa Taarifa</h3>

                <p>
                    Mfumo umezingatia viwango vya juu vya usalama
                    wa taarifa za watumiaji.
                </p>

            </div>

        </div>

    </section>

    <!-- ABOUT -->
    <section class="about"
             id="about">

        <div class="about-card">

            <div class="about-grid">

                <div>

                    <img src="{{ asset('images/ppppppp.jpg') }}"
                         alt="About">

                </div>

                <div class="about-content">

                    <h2>
                        Kuhusu EduAttend
                    </h2>

                    <p>

                        EduAttend ni mfumo wa kisasa uliotengenezwa kusaidia
                        shule na halmashauri kusimamia mahudhurio ya walimu
                        kwa njia rahisi, salama na yenye uwazi.

                    </p>

                    <p>

                        Mfumo huu unawezesha viongozi wa elimu kufuatilia
                        taarifa za mahudhurio kwa wakati halisi pamoja na
                        kupata ripoti sahihi kwa ajili ya maamuzi ya kiutawala.

                    </p>

                    <div class="about-list">


                        <div class="about-item">

                            <i class="bi bi-check-circle-fill"></i>

                            Mahudhurio kwa GPS

                        </div>

                        <div class="about-item">

                            <i class="bi bi-check-circle-fill"></i>

                            Ripoti za muda halisi

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer>

        <p>

            © {{ date('Y') }}
            Chemba District Council · Tanzania

            <br>

            Teacher Attendance Management System

        </p>

    </footer>

</body>
</html>