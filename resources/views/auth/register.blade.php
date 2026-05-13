
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usajili | EduAttend</title>

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

        /* BACKGROUND WATERMARK */

        .watermark{
            position:fixed;
            inset:0;
            background:url('{{ asset('images/ppppppp.jpg') }}') center center no-repeat;
            background-size:420px;
            opacity:.04;
            z-index:0;
            pointer-events:none;
        }

        /* TOP BAR */

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

        /* MAIN SECTION */

        .main-wrapper{
            position:relative;
            z-index:2;
            width:100%;
            padding:40px 15px;
            display:flex;
            justify-content:center;
        }

        .register-card{
            width:100%;
            max-width:960px;
            background:white;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 15px 50px rgba(15,23,42,.08);
            display:grid;
            grid-template-columns:1fr 1.1fr;
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

            padding:50px 40px;

            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .left-panel .badge-gov{
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
            gap:16px;
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
            padding:40px;
        }

        .form-title{
            margin-bottom:28px;
        }

        .form-title h3{
            font-size:28px;
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
            margin-bottom:18px;
        }

        .input-group-text{
            border-radius:14px 0 0 14px;
            border:1px solid var(--border);
            background:#f8fafc;
            color:#64748b;
            padding:0 16px;
        }

        .form-control,
        .form-select{
            height:54px;
            border-radius:0 14px 14px 0;
            border:1px solid var(--border);
            font-size:15px;
            transition:.3s;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--primary-light);
            box-shadow:0 0 0 4px rgba(13,110,253,.1);
        }

        .form-control::placeholder{
            color:#94a3b8;
        }

        .toggle-password{
            cursor:pointer;
        }

        .btn-register{
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

        .btn-register:hover{
            transform:translateY(-2px);
        }

        .btn-login{
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

        .btn-login:hover{
            background:#eef5ff;
            color:var(--primary);
        }

        .footer-note{
            margin-top:25px;
            text-align:center;
            color:#64748b;
            font-size:13px;
            line-height:1.7;
        }

        /* MOBILE */

        @media(max-width:992px){

            .register-card{
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
            }

            .right-panel{
                padding:26px 18px;
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
            .form-select,
            .btn-register,
            .btn-login{
                height:52px;
                font-size:14px;
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

        <div class="register-card">

            <!-- LEFT -->
            <div class="left-panel">

                <div class="badge-gov">
                    <div class="dot"></div>
                    Mfumo Rasmi wa Mahudhurio
                </div>

                <h2>Usajili wa Mfumo wa Mahudhurio</h2>

                <p>
                    Mfumo rasmi wa kisasa unaotumiwa kusimamia mahudhurio ya walimu,
                    taarifa za shule, pamoja na ufuatiliaji wa mahudhurio kwa uhakika.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        Mfumo salama wenye viwango vya serikali
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-geo-alt"></i>
                        Uthibitishaji wa eneo kwa GPS
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-bar-chart"></i>
                        Taarifa za muda halisi kwa wasimamizi
                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="right-panel">

                <div class="form-title">
                    <h3>Jisajili</h3>
                    <p>Jaza taarifa zako kwa usahihi ili kufungua akaunti ya mfumo.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- FIRST NAME -->
                    <label class="form-label">Jina la Kwanza</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               name="first_name"
                               class="form-control"
                               placeholder="Ingiza jina la kwanza"
                               required>
                    </div>

                    <!-- MIDDLE NAME -->
                    <label class="form-label">Jina la Kati</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               name="middle_name"
                               class="form-control"
                               placeholder="Ingiza jina la kati">
                    </div>

                    <!-- LAST NAME -->
                    <label class="form-label">Jina la Mwisho</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               name="last_name"
                               class="form-control"
                               placeholder="Ingiza jina la mwisho"
                               required>
                    </div>

                    <!-- CHECK NUMBER -->
                    <label class="form-label">Check Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-credit-card"></i>
                        </span>
                        <input type="text"
                               name="check_number"
                               class="form-control"
                               placeholder="Ingiza check number"
                               required>
                    </div>

                    <!-- EMAIL -->
                    <label class="form-label">Barua Pepe</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="example@email.com"
                               required>
                    </div>

                    <!-- PHONE -->
                    <label class="form-label">Namba ya Simu</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input type="tel"
                               name="phone"
                               class="form-control"
                               placeholder="+2557XXXXXXXX"
                               pattern="^(\+255|0)[67][0-9]{8}$"
                               required>
                    </div>

                    <!-- SEX -->
                    <label class="form-label">Jinsia</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-gender-ambiguous"></i>
                        </span>
                        <select name="sex" class="form-select" required>
                            <option value="">-- Chagua Jinsia --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
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
                               placeholder="Ingiza password"
                               required>

                        <span class="input-group-text toggle-password"
                              onclick="togglePassword('password','eye1')">
                            <i class="bi bi-eye" id="eye1"></i>
                        </span>
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <label class="form-label">Thibitisha Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="password"
                               name="password_confirmation"
                               id="confirmPassword"
                               class="form-control"
                               placeholder="Rudia password"
                               required>

                        <span class="input-group-text toggle-password"
                              onclick="togglePassword('confirmPassword','eye2')">
                            <i class="bi bi-eye" id="eye2"></i>
                        </span>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus"></i>
                        Sajili Akaunti
                    </button>

                    <!-- LOGIN -->
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Tayari Nina Akaunti
                    </a>

                </form>

                <div class="footer-note">
                    © {{ date('Y') }} Chemba District Council
                </div>

            </div>

        </div>

    </div>

    <script>

        function togglePassword(inputId, eyeId){

            let password = document.getElementById(inputId);
            let eye = document.getElementById(eyeId);

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
