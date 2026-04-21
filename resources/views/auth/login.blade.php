<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Attendance Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }

        .header {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .body {
            background: white;
            padding: 30px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="login-card">

    <!-- HEADER -->
    <div class="header">
        <h2>🏫 Teacher Login</h2>
        <small>Attendance System</small>
    </div>

    <!-- BODY -->
    <div class="body">

        <!-- Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- CHECK NUMBER -->
            <div class="mb-3">
                <label>Check Number</label>
                <input type="text"
                       name="check_number"
                       class="form-control"
                       value="{{ old('check_number') }}"
                       required>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label>Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <!-- REMEMBER -->
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input">
                <label class="form-check-label">Remember Me</label>
            </div>

            <!-- BUTTON -->
            <button class="btn btn-primary w-100">
                🔐 Login
            </button>

        </form>

    </div>

</div>

</body>
</html>