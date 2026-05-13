
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ingia Mfumo | EduAttend</title>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        :root{
            --primary:#005bac;
            --primary-light:#0d6efd;
            --secondary:#0b2239;
            --gold:#f4b400;
            --white:#ffffff;
            --text:#1e293b;
            --muted:#64748b;
            --border:#dbe4ef;
            --bg:#eef3f9;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            min-height:100vh;
            overflow-x:hidden;
            position:relative;
        }

        /* WATERMARK */

        .watermark{
            position:fixed;
            inset:0;
            background:url('{{ asset('images/ppppppp.jpg') }}') center center no-repeat;
            background-size:420px;
            opacity:.04;
            z-index:0;
            pointer-events:none;
        }

        /* TOPBAR */

        .topbar{
            background:linear-gradient(90deg,var(--secondary),var(--primary));
            color:white;
            padding:12px 20px;
            position:relative;
            z-index:5;
            box-shadow:0 2px 10px rgba(0,0,0,.08);
        }

        .topbar-inner{
            max-width:1200px;
            margin:auto;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:14px;
            text-align:center;
        }

        .topbar img{
            width:58px;
            height:58px;
            border-radius:50%;
            object-fit:cover;
            background:white;
            padding:3px;
        }

        .topbar h1{
            font-size:22px;
            font-weight:800;
            margin:0;
            line-height:1.2;
        }

        .topbar p{
            margin:0;
            font-size:13px;
            opacity:.9;
        }

        /* MAIN WRAPPER */

        .main-wrapper{
            position:relative;
            z-index:2;
            width:100%;
            min-height:calc(100vh - 82px);
            padding:40px 15px;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        /* LOGIN CARD */

        .login-card{
            width:100%;
            max-width:980px;
            background:white;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 15px 50px rgba(15,23,42,.08);
            display:grid;
            grid-template-columns:1fr 1fr;
            border:1px solid #e5edf7;
        }

        /* LEFT PANEL */

        .left-panel{
            background:
                linear-gradient(rgba(4,18,38,.88), rgba(0,91,172,.88)),
                url('{{ asset('images/ppppppp.jpg') }}');

            background-size:cover;
            background-position:center;

            color:white;

            padding:55px 45px;

            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .gov-badge{
            display:inline-flex;
            align-items:center;
            gap:10px;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.15);
            padding:10px 18px;
            border-radius:999px;
            font-size:13px;
            margin-bottom:25px;
            width:max-content;
        }

        .dot{
            width:10px;
            height:10px;
            background:#22c55e;
            border-radius:50%;
        }

        .left-panel h2{
            font-size:42px;
            font-weight:800;
            line-height:1.15;
            margin-bottom:18px;
        }

        .left-panel p{
            color:#dbeafe;
            line-height:1.9;
            font-size:15px;
        }

        .feature-list{
            margin-top:35px;
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        .feature-item{
            display:flex;
            align-items:center;
            gap:12px;
            font-size:15px;
        }

        .feature-item i{
            color:#facc15;
            font-size:18px;
        }

        /* RIGHT PANEL */

        .right-panel{
            padding:45px 40px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .logo-wrap{
            text-align:center;
            margin-bottom:18px;
        }

        .logo-wrap img{
            width:88px;
            height:88px;
            border-radius:50%;
            object-fit:cover;
            background:white;
            padding:4px;
            box-shadow:0 10px 20px rgba(0,0,0,.08);
        }

        .form-title{
            text-align:center;
            margin-bottom:28px;
        }

        .form-title h3{
            font-size:30px;
            color:var(--secondary);
            font-weight:800;
            margin-bottom:8px;
        }

        .form-title p{
            color:var(--muted);
            margin:0;
            line-height:1.7;
        }

        .alert{
            border-radius:14px;
            font-size:14px;
        }

        .form-label{
            font-size:14px;
            font-weight:600;
            color:#334155;
            margin-bottom:8px;
        }

        .input-group{
            margin-bottom:20px;
        }

        .input-group-text{
            border-radius:14px 0 0 14px;
            border:1px solid var(--border);
            background:#f8fafc;
            color:#64748b;
            padding:0 16px;
        }

        .form-control{
            height:56px;
            border-radius:0 14px 14px 0;
            border:1px solid var(--border);
            font-size:15px;
            transition:.3s;
        }

        .form-control:focus{
            border-color:var(--primary-light);
            box-shadow:0 0 0 4px rgba(13,110,253,.1);
        }

        .form-control::placeholder{
            color:#94a3b8;
        }

        .toggle-password{
            cursor:pointer;
        }

        .remember-wrap{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            margin-bottom:24px;
            flex-wrap:wrap;
        }

        .form-check-label{
            color:#475569;
            font-size:14px;
        }

        .forgot-link{
            text-decoration:none;
            font-size:14px;
            font-weight:600;
            color:var(--primary);
        }

        .forgot-link:hover{
            color:var(--primary-light);
        }

        /* BUTTONS */

        .btn-login{
            width:100%;
            height:56px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,var(--primary),var(--primary-light));
            color:white;
            font-weight:700;
            font-size:15px;
            transition:.3s;
            box-shadow:0 10px 25px rgba(0,91,172,.2);
        }

        .btn-login:hover{
            transform:translateY(-2px);
        }

        .btn-register{
            width:100%;
            height:54px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            text-decoration:none;
            color:var(--primary);
            border:1px solid #cfe0ff;
            background:#f8fbff;
            font-weight:700;
            margin-top:14px;
            transition:.3s;
        }

        .btn-register:hover{
            background:#eef5ff;
            color:var(--primary);
        }

        .footer-note{
            margin-top:24px;
            text-align:center;
            color:#64748b;
            font-size:13px;
            line-height:1.7;
        }

        /* MOBILE */

        @media(max-width:992px){

            .login-card{
                grid-template-columns:1fr;
            }

            .left-panel{
                padding:35px 25px;
            }

            .left-panel h2{
                font-size:34px;
            }
        }

        @media(max-width:576px){

            .topbar-inner{
                flex-direction:column;
            }

            .topbar h1{
                font-size:18px;
            }

            .main-wrapper{
                padding:18px 12px 30px;
                min-height:auto;
            }

            .right-panel{
                padding:28px 18px;
            }

            .left-panel{
                padding:28px 20px;
            }

            .left-panel h2{
                font-size:28px;
            }

            .form-title h3{
                font-size:24px;
            }

            .form-control,
            .btn-login,
            .btn-register{
                height:52px;
                font-size:14px;
            }

            .logo-wrap img{
                width:76px;
                height:76px;
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

            <img src="{{ asset('images/ppppppp.jpg') }}" alt="Logo">

            <div>
                <h1>Chemba District Council</h1>
                <p>Teacher Attendance Management System (EduAttend)</p>
            </div>

        </div>

    </div>

    <!-- MAIN -->
    <div class="main-wrapper">

        <div class="login-card">

            <!-- LEFT PANEL -->
            <div class="left-panel">

                <div class="gov-badge">
                    <div class="dot"></div>
                    Mfumo Rasmi wa Mahudhurio
                </div>

                <h2>Karibu EduAttend</h2>

                <p>
                    Mfumo rasmi wa usimamizi wa mahudhurio ya walimu kwa shule za
                    halmashauri. Ingia kwenye mfumo ili kuendelea kutumia huduma.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        Mfumo salama wenye ulinzi wa taarifa
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-geo-alt"></i>
                        Mahudhurio kwa GPS na muda halisi
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-bar-chart"></i>
                        Ripoti za kisasa kwa wasimamizi
                    </div>

                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">

                <div class="logo-wrap">
                    <img src="{{ asset('images/ppppppp.jpg') }}" alt="Logo">
                </div>

                <div class="form-title">
                    <h3>Ingia Mfumo</h3>
                    <p>Weka taarifa zako ili kuingia kwenye mfumo wa mahudhurio.</p>
                </div>

                <!-- ERRORS -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- CHECK NUMBER -->
                    <label class="form-label">Check Number</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>

                        <input type="text"
                               name="check_number"
                               class="form-control"
                               placeholder="Ingiza Check Number"
                               value="{{ old('check_number') }}"
                               required>

                    </div>

                    <!-- PASSWORD -->
                    <label class="form-label">Password</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Ingiza Password"
                               required>

                        <span class="input-group-text toggle-password"
                              onclick="togglePassword()">

                            <i class="bi bi-eye" id="eyeIcon"></i>

                        </span>

                    </div>

                    <!-- REMEMBER -->
                    <div class="remember-wrap">

                        <div class="form-check">

                            <input type="checkbox"
                                   name="remember"
                                   class="form-check-input"
                                   id="remember">

                            <label class="form-check-label" for="remember">
                                Nikumbuke
                            </label>

                        </div>

                        <a href="#" class="forgot-link">
                            Umesahau Password?
                        </a>

                    </div>

                    <!-- LOGIN BUTTON -->
                    <button class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Ingia Kwenye Mfumo
                    </button>

                    <!-- REGISTER BUTTON -->
                    <a href="{{ route('register') }}"
                       class="btn-register">

                        <i class="bi bi-person-plus"></i>
                        Fungua Akaunti Mpya

                    </a>

                </form>

                <!-- FOOTER -->
                <div class="footer-note">
                    © {{ date('Y') }} Chemba District Council · Tanzania
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT -->
    <script>

        function togglePassword(){

            let password = document.getElementById('password');
            let eye = document.getElementById('eyeIcon');

            if(password.type === 'password'){

                password.type = 'text';

                eye.classList.remove('bi-eye');
                eye.classList.add('bi-eye-slash');

            }else{

                password.type = 'password';

                eye.classList.remove('bi-eye-slash');
                eye.classList.add('bi-eye');
            }
        }

    </script>

</body>
</html>