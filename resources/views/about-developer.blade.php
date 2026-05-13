
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Kuhusu Mtengenezaji | EduAttend
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

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--text);
            overflow-x:hidden;
            position:relative;
        }

        a{
            text-decoration:none;
        }

        /* WATERMARK */

        .watermark{
            position:fixed;
            inset:0;
            background:
                url('{{ asset('images/ppppppp.jpg') }}')
                center center no-repeat;

            background-size:420px;

            opacity:.03;

            z-index:0;
            pointer-events:none;
        }

        /* TOPBAR */

        .topbar{
            background:
                linear-gradient(
                90deg,
                var(--secondary),
                var(--primary)
                );

            color:white;
            padding:15px 20px;
            position:relative;
            z-index:5;
        }

        .topbar-inner{
            max-width:1200px;
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
            object-fit:cover;
            background:white;
            padding:3px;
        }

        .topbar h2{
            margin:0;
            font-size:24px;
            font-weight:800;
        }

        .topbar p{
            margin:0;
            font-size:13px;
            opacity:.9;
        }

        /* MAIN */

        .main-wrapper{
            position:relative;
            z-index:2;
            padding:70px 20px 90px;
        }

        .container-custom{
            max-width:1200px;
            margin:auto;
        }

        /* HERO */

        .hero-card{
            background:white;
            border-radius:30px;
            overflow:hidden;
            border:1px solid #e2e8f0;

            box-shadow:
                0 15px 40px rgba(15,23,42,.06);

            display:grid;
            grid-template-columns:420px 1fr;
        }

        .hero-image{
            background:
                linear-gradient(
                rgba(0,91,172,.12),
                rgba(0,91,172,.12)
                );

            padding:35px;

            display:flex;
            align-items:center;
            justify-content:center;
        }

        .hero-image img{
            width:100%;
            max-width:320px;
            border-radius:28px;
            object-fit:cover;
            border:5px solid white;

            box-shadow:
                0 20px 35px rgba(0,0,0,.12);
        }

        .hero-content{
            padding:55px 50px;
        }

        .badge-custom{
            display:inline-flex;
            align-items:center;
            gap:10px;

            background:#dbeafe;
            color:#1d4ed8;

            padding:10px 18px;
            border-radius:999px;
            font-size:13px;
            font-weight:700;

            margin-bottom:22px;
        }

        .dot{
            width:10px;
            height:10px;
            border-radius:50%;
            background:#22c55e;
        }

        .hero-content h1{
            font-size:52px;
            line-height:1.1;
            color:var(--secondary);
            margin-bottom:18px;
            font-weight:800;
        }

        .hero-content h1 span{
            color:var(--primary);
        }

        .hero-content p{
            color:var(--muted);
            line-height:1.9;
            font-size:16px;
            margin-bottom:20px;
        }

        /* INFO GRID */

        .info-grid{
            margin-top:35px;

            display:grid;
            grid-template-columns:
                repeat(auto-fit,minmax(220px,1fr));

            gap:20px;
        }

        .info-card{
            background:white;
            border-radius:22px;
            padding:30px 25px;
            border:1px solid #e2e8f0;

            box-shadow:
                0 10px 25px rgba(15,23,42,.04);

            transition:.3s;
        }

        .info-card:hover{
            transform:translateY(-6px);
        }

        .info-icon{
            width:65px;
            height:65px;
            border-radius:18px;

            display:flex;
            align-items:center;
            justify-content:center;

            background:#eff6ff;
            color:var(--primary);

            font-size:28px;

            margin-bottom:20px;
        }

        .info-card h3{
            font-size:20px;
            font-weight:700;
            color:var(--secondary);
            margin-bottom:12px;
        }

        .info-card p,
        .info-card a{
            color:var(--muted);
            line-height:1.8;
            font-size:15px;
        }

        .info-card a:hover{
            color:var(--primary);
        }

        /* SKILLS */

        .skills-section{
            margin-top:45px;
        }

        .section-title{
            text-align:center;
            margin-bottom:40px;
        }

        .section-title h2{
            font-size:40px;
            color:var(--secondary);
            font-weight:800;
            margin-bottom:12px;
        }

        .section-title p{
            color:var(--muted);
            max-width:700px;
            margin:auto;
            line-height:1.8;
        }

        .skills-grid{
            display:grid;
            grid-template-columns:
                repeat(auto-fit,minmax(240px,1fr));

            gap:22px;
        }

        .skill-card{
            background:white;
            border-radius:24px;
            padding:28px;
            border:1px solid #e2e8f0;

            box-shadow:
                0 10px 30px rgba(15,23,42,.04);
        }

        .skill-card h4{
            color:var(--secondary);
            margin-bottom:18px;
            font-size:20px;
            font-weight:700;
        }

        .skill-item{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:14px;
            color:#334155;
            font-weight:600;
        }

        .skill-item i{
            color:#16a34a;
        }

        /* CONTACT */

        .contact-section{
            margin-top:50px;
        }

        .contact-card{
            background:
                linear-gradient(
                135deg,
                var(--secondary),
                var(--primary)
                );

            border-radius:30px;
            padding:50px 35px;
            color:white;
            text-align:center;
        }

        .contact-card h2{
            font-size:42px;
            font-weight:800;
            margin-bottom:15px;
        }

        .contact-card p{
            max-width:700px;
            margin:auto auto 30px;
            line-height:1.9;
            opacity:.92;
        }

        .contact-buttons{
            display:flex;
            justify-content:center;
            gap:16px;
            flex-wrap:wrap;
        }

        .btn-contact{
            padding:14px 24px;
            border-radius:14px;
            font-weight:700;
            display:inline-flex;
            align-items:center;
            gap:10px;
            transition:.3s;
        }

        .btn-white{
            background:white;
            color:var(--primary);
        }

        .btn-white:hover{
            transform:translateY(-2px);
            color:var(--primary);
        }

        .btn-outline{
            border:1px solid rgba(255,255,255,.2);
            background:rgba(255,255,255,.08);
            color:white;
        }

        .btn-outline:hover{
            background:rgba(255,255,255,.15);
            color:white;
        }

        /* FOOTER */

        footer{
            margin-top:60px;
            text-align:center;
            color:#64748b;
            font-size:14px;
            line-height:1.8;
        }

        /* MOBILE */

        @media(max-width:992px){

            .hero-card{
                grid-template-columns:1fr;
            }

            .hero-image{
                padding-bottom:0;
            }

            .hero-content{
                padding:35px 25px 40px;
            }
        }

        @media(max-width:768px){

            .topbar-inner{
                flex-direction:column;
            }

            .hero-content h1{
                font-size:38px;
            }

            .section-title h2,
            .contact-card h2{
                font-size:32px;
            }

            .hero-image img{
                max-width:250px;
            }

            .contact-buttons{
                flex-direction:column;
            }

            .btn-contact{
                width:100%;
                justify-content:center;
            }

            .watermark{
                background-size:240px;
            }
        }

        .back-home-btn{
    display:inline-flex;
    align-items:center;
    gap:10px;

    padding:12px 20px;

    background:#005bac;
    color:white;

    border-radius:14px;

    font-weight:700;
    text-decoration:none;

    transition:.3s;

    box-shadow:
        0 10px 25px rgba(0,91,172,.18);
}

.back-home-btn:hover{
    background:#004a8f;
    transform:translateY(-2px);
    color:white;
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
                    EduAttend - Teacher Attendance Management System
                </p>

            </div>

        </div>

    </div>

    <!-- MAIN -->
    <div class="main-wrapper">

        <div class="container-custom">

            <!-- HERO -->
            <div class="hero-card">

                <!-- IMAGE -->
                <div class="hero-image">

                
                    <img src="{{ asset('images/dotto.jpeg') }}"
                         alt="Developer">

                </div>

                <!-- CONTENT -->
                <div class="hero-content">
<a href="{{ url('/') }}" class="back-home-btn">
    <i class="bi bi-arrow-left"></i>
    Rudi Home
</a>
                    <div class="badge-custom">

                        <div class="dot"></div>

                        Mtengenezaji wa Mfumo

                    </div>

                    <h1>
                        Mimi ni
                        <span>
                            Dotto T. Charles                        </span>
                    </h1>

                    <p>
                        Mimi ni msanidi wa mifumo ya kompyuta (Software Developer)
                        mwenye uzoefu katika kutengeneza mifumo ya kisasa ya
                        serikali, elimu na taasisi mbalimbali.
                    </p>

                    <p>
                        Mfumo wa EduAttend umetengenezwa kwa lengo la kuboresha
                        usimamizi wa mahudhurio ya walimu kwa kutumia teknolojia
                        za kisasa kama GPS, taarifa za muda halisi na usalama wa data.
                    </p>

                    <p>
                        Nina passion kubwa ya kutumia teknolojia kutatua changamoto
                        za kijamii na kuboresha utoaji wa huduma kwa taasisi za umma.
                    </p>

                </div>

            </div>

            <!-- INFO GRID -->
            <div class="info-grid">

                <!-- EDUCATION -->
                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <h3>
                        Elimu
                    </h3>

                    <p>
                        Bachelor of Science with Education in Mathematics and Information Communication Technology(ICT)-Mzumbe University
                    </p>

                </div>

                <!-- EXPERIENCE -->
                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>

                    <h3>
                        Utaalamu
                    </h3>

                    <p>
                        Laravel, PHP, JavaScript, C, C++, Python, Java,
                        MySQL, PostgreSQL, Bootstrap,
                        Mobile Friendly UI/UX,
                        Web Development.
                    </p>

                </div>

                <!-- CONTACT -->
                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <h3>
                        Mawasiliano
                    </h3>

                    <p>
                        Simu: +255-621-015-458
                        <br>
                        Email: tittocharles@gmail.com
                    </p>

                </div>

                <!-- LOCATION -->
                <div class="info-card">

                    <div class="info-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <h3>
                        Mahali
                    </h3>

                    <p>
                        Chemba District Council
                        <br>
                        Available for projects & collaboration.
                    </p>

                </div>

            </div>

            <!-- SKILLS -->
            <div class="skills-section">

                <div class="section-title">

                    <h2>
                        Uwezo na Teknolojia
                    </h2>

                    <p>
                        Baadhi ya teknolojia na maeneo ninayofanyia kazi.
                    </p>

                </div>

                <div class="skills-grid">

                    <div class="skill-card">

                        <h4>
                            Backend Development
                        </h4>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            Laravel Framework
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            PHP & REST APIs
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            MySQL Database
                        </div>

                    </div>

                    <div class="skill-card">

                        <h4>
                            Frontend Development
                        </h4>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            HTML5 & CSS3
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            Bootstrap & Responsive Design
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            JavaScript UI/UX
                        </div>

                    </div>

                    <div class="skill-card">

                        <h4>
                            Mfumo Maalum
                        </h4>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            GPS Attendance Systems
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            Online Land Registration and Verification System                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            School Management Systems
                        </div>

                        <div class="skill-item">
                            <i class="bi bi-check-circle-fill"></i>
                            Shop Management Systems
                        </div>

                    </div>

                </div>

            </div>

            <!-- CONTACT -->
            <div class="contact-section">

                <div class="contact-card">

                    <h2>
                        Wasiliana Nami
                    </h2>

                    <p>
                        Kwa mawasiliano, ushauri wa mifumo,
                        website development, au miradi ya teknolojia,
                        unaweza kuwasiliana nami kupitia njia zifuatazo.
                    </p>

                    <div class="contact-buttons">

                        <a href="tel:+255621015458"
                           class="btn-contact btn-white">

                            <i class="bi bi-telephone-fill"></i>
                            Piga Simu

                        </a>

                        <a href="mailto:tittocharles@gmail.com"
                           class="btn-contact btn-outline">

                            <i class="bi bi-envelope-fill"></i>
                            Tuma Email

                        </a>

                        <a href="https://wa.me/255764409029"
                    target="_blank"
                           class="btn-contact btn-outline">

                            <i class="bi bi-whatsapp"></i>
                            WhatsApp

                        </a>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <footer>

                © {{ date('Y') }} EduAttend System
                <br>
                Designed & Developed by Dotto T. Charles

            </footer>

        </div>

    </div>

</body>
</html>