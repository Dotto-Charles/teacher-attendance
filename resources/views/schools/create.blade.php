@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">🏫 Register New School</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('schools.store') }}">
        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>School Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>School Code</label>
                <input type="text" name="code" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Region</label>
                <input type="text" name="region" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>District</label>
                <input type="text" name="district" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Ward</label>
                <input type="text" name="ward" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Street</label>
                <input type="text" name="street" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Radius (meters)</label>
                <input type="number" name="radius" value="500" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Head Teacher</label>
                <select name="head_teacher_id" class="form-control">
                    <option value="">-- Select Head Teacher --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <button class="btn btn-primary">
            ➕ Create School
        </button>

    </form>
</div>
@endsection