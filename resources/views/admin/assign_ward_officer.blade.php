<x-layout>

<div class="container">

    <h3 class="mb-3">🏢 Assign Ward Officers</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="card p-3 mb-4">

        <form method="POST" action="{{ route('admin.assign.ward.store') }}">
            @csrf

            <div class="row">

                <!-- TEACHER -->
                <div class="col-md-4">
                    <label>Teacher</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- WARD -->
                <div class="col-md-4">
                    <label>Ward</label>
                    <select name="ward_id" class="form-control" required>
                        <option value="">Select Ward</option>
                        @foreach($wards as $ward)
                            <option value="{{ $ward->id }}">
                                {{ $ward->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        Assign Ward Officer
                    </button>
                </div>

            </div>

        </form>

    </div>

    <!-- ASSIGNED LIST -->
    <div class="card p-3">

        <h5>Current Ward Officers</h5>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Ward</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody>
                @foreach($assigned as $user)
                <tr>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->ward->name ?? 'N/A' }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

</x-layout>