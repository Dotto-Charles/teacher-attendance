<!DOCTYPE html>
<html>
<head>
    <title>Officer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>🏛️ Education Officer Dashboard</h2>

    <div class="alert alert-dark">
        Welcome, {{ auth()->user()->name }}
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card p-3">
                <h5>🏫 Schools Overview</h5>
                <button class="btn btn-primary">View Schools</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>📍 Ward Attendance</h5>
                <button class="btn btn-success">View Data</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>📊 Reports</h5>
                <button class="btn btn-dark">Generate Reports</button>
            </div>
        </div>

    </div>

</div>

</body>
</html>