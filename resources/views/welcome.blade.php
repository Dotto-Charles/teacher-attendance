<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduAttend | Mfumo wa Mahudhurio</title>

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#0b1220;
            --surface:rgba(255,255,255,0.05);
            --border:rgba(255,255,255,0.08);
            --text:#ffffff;
            --text2:#cbd5e1;
            --primary:#2563eb;
            --primary2:#3b82f6;
            --font:'Space Grotesk',sans-serif;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:var(--font);
            background:#000;
            color:var(--text);
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        /* ===== BACKGROUND ===== */
        .bg-image{
            position:fixed;
            inset:0;
            background:
                linear-gradient(rgba(7,10,20,.82), rgba(7,10,20,.88)),
                url('/mnt/data/ppppppp.jpg');
            background-size:cover;
            background-position:center;
            filter:blur(2px);
            transform:scale(1.05);
            z-index:-2;
        }

        .bg-overlay{
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.35);
            z-index:-1;
        }

        /* ===== NAVBAR ===== */
        .navbar{
            position:fixed;
            top:0;
            left:0;
            right:0;
            z-index:100;
            backdrop-filter:blur(14px);
            background:rgba(5,10,20,.55);
            border-bottom:1px solid var(--border);
        }

        .nav-inner{
            max-width:1150px;
            margin:auto;
            padding:16px 20px;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .brand img{
            width:44px;
            height:44px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid rgba(255,255,255,.12);
            background:#fff;
        }

        .brand-text h2{
            font-size:18px;
            font-weight:700;
            line-height:1;
        }

        .brand-text span{
            font-size:11px;
            color:var(--text2);
        }

        .nav-links{
            display:flex;
            align-items:center;
            gap:10px;
        }

        .nav-link{
            padding:10px 14px;
            border-radius:8px;
            font-size:14px;
            color:var(--text2);
            transition:.2s;
        }

        .nav-link:hover{
            background:rgba(255,255,255,.06);
            color:#fff;
        }

        .btn{
            padding:11px 18px;
            border-radius:10px;
            font-size:14px;
            font-weight:600;
            transition:.2s;
        }

        .btn-login{
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.08);
            color:#fff;
        }

        .btn-login:hover{
            background:rgba(255,255,255,.1);
        }

        .btn-primary{
            background:linear-gradient(135deg,var(--primary),var(--primary2));
            color:white;
            box-shadow:0 10px 25px rgba(37,99,235,.3);
        }

        .btn-primary:hover{
            transform:translateY(-2px);
        }

        /* ===== HERO ===== */
        .hero{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            padding:130px 20px 80px;
        }

        .hero-content{
            max-width:760px;
        }

        .hero-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.08);
            padding:8px 16px;
            border-radius:999px;
            margin-bottom:24px;
            font-size:13px;
            color:#dbeafe;
        }

        .dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#22c55e;
        }

        .hero h1{
            font-size:clamp(36px,6vw,68px);
            line-height:1.1;
            margin-bottom:22px;
            font-weight:800;
        }

        .highlight{
            background:linear-gradient(135deg,#60a5fa,#93c5fd);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .hero p{
            font-size:18px;
            line-height:1.8;
            color:var(--text2);
            margin-bottom:35px;
        }

        .hero-buttons{
            display:flex;
            justify-content:center;
            gap:14px;
            flex-wrap:wrap;
        }

        /* ===== ABOUT ===== */
        .about{
            padding:90px 20px;
        }

        .about-card{
            max-width:1000px;
            margin:auto;
            background:rgba(10,15,25,.72);
            border:1px solid rgba(255,255,255,.08);
            border-radius:24px;
            backdrop-filter:blur(12px);
            padding:50px 35px;
            text-align:center;
        }

        .about-card h2{
            font-size:38px;
            margin-bottom:18px;
        }

        .about-card p{
            color:var(--text2);
            line-height:1.9;
            max-width:760px;
            margin:auto;
            font-size:16px;
        }

        /* ===== FOOTER ===== */
        footer{
            border-top:1px solid rgba(255,255,255,.08);
            padding:30px 20px;
            text-align:center;
            color:#94a3b8;
            font-size:13px;
            background:rgba(0,0,0,.35);
            backdrop-filter:blur(10px);
        }

        /* ===== MOBILE ===== */
        @media(max-width:768px){

            .nav-inner{
                flex-direction:column;
                gap:16px;
            }

            .nav-links{
                flex-wrap:wrap;
                justify-content:center;
            }

            .hero p{
                font-size:16px;
            }

            .about-card{
                padding:35px 24px;
            }

            .about-card h2{
                font-size:30px;
            }
        }

    </style>
</head>

<body>

    <!-- BACKGROUND -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-inner">

            <div class="brand">
                <img src="/mnt/data/ppppppp.jpg" alt="Chemba Logo">

                <div class="brand-text">
                    <h2>EduAttend</h2>
                    <span>Attendance Management System</span>
                </div>
            </div>

            <div class="nav-links">

                <a href="#about" class="nav-link">About Us</a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-login">
                        Ingia
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Jiandikishe
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Dashboard
                    </a>
                @endguest

            </div>

        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">

        <div class="hero-content">

            <div class="hero-badge">
                <div class="dot"></div>
                Mfumo wa Mahudhurio · Chemba District
            </div>

            <h1>
                Mfumo wa Kisasa wa
                <span class="highlight">
                    Mahudhurio ya Walimu
                </span>
            </h1>

            <p>
                Mfumo wa kisasa unaorahisisha usimamizi wa mahudhurio ya walimu
                kwa kutumia GPS, taarifa za haraka, na ufuatiliaji wa uhakika
                kwa shule zote za halmashauri.
            </p>

            <div class="hero-buttons">

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Ingia Mfumo
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-login">
                        Fungua Akaunti
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Nenda Dashboard
                    </a>
                @endguest

            </div>

        </div>

    </section>

    <!-- ABOUT -->
    <section class="about" id="about">

        <div class="about-card">

            <h2>About Us</h2>

            <p>
                EduAttend ni mfumo wa kisasa ulioundwa kusaidia shule na
                halmashauri kusimamia mahudhurio ya walimu kwa njia rahisi,
                salama na ya kisasa. Mfumo huu unawezesha ufuatiliaji wa
                mahudhurio kwa kutumia GPS pamoja na taarifa za muda halisi
                kwa viongozi wa elimu na wasimamizi wa shule.
            </p>

        </div>

    </section>

    <!-- FOOTER -->
    <footer>
        © {{ date('Y') }} EduAttend · Chemba District Council · Tanzania
    </footer>

</body>
</html>