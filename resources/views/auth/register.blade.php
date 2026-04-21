<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
</head>

<body>

<div class="card">

    <h4 class="title mb-3">🏫 Teacher Registration</h4>

    <!-- Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div class="mb-2">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <!-- Middle Name -->
        <div class="mb-2">
            <label>Middle Name</label>
            <input type="text" name="middle_name" class="form-control">
        </div>

        <!-- Last Name -->
        <div class="mb-2">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <!-- Check Number -->
        <div class="mb-2">
            <label>Check Number</label>
            <input type="text" name="check_number" class="form-control" required>
        </div>

        <!-- Email -->
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <!-- Phone -->
        <div class="mb-2">
            <label>Phone</label> <br>
            <input type="tel" name="phone"
            placeholder="+2557XXXXXXXX or 07XXXXXXXX"
            pattern="^(\+255|0)[67][0-9]{8}$"
            title="Enter a valid phone number" required>
        </div>

        <!-- Sex -->
        <div class="mb-2">
            <label>Sex</label>
            <select name="sex" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Register
        </button>

    </form>

    <div class="text-center mt-3">
        Already have account?
        <a href="{{ route('login') }}">Login</a>
    </div>

</div>

</body>
</html>