<x-layout>

    <div class="row">

        <!-- CARD 1 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>📅 Attendance</h4>
                    <p>Mark daily attendance</p>

                    <a href="{{ route('attendance.index') }}" class="btn btn-primary">
                        Open
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>👤 Profile</h4>
                    <p>Manage your account</p>

                    <a href="/profile" class="btn btn-success">
                        Open
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD 3 -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4>🏫 School Info</h4>
                    <p>View assigned school</p>

                    <button class="btn btn-info">
                        View
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- INFO SECTION -->
    <div class="mt-4">

        <div class="card">
            <div class="card-body">
                <h5>📊 System Status</h5>

                <p><b>Name:</b> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                <p><b>Role:</b> {{ auth()->user()->role }}</p>
                <p><b>Status:</b> {{ auth()->user()->status }}</p>

            </div>
        </div>

    </div>

</x-layout>