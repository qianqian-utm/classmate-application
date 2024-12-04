@extends('layouts.app')

@section('content')
    <h1>Add User</h1>

    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="ic_number" class="form-label">IC Number/Passport</label>
            <p>*Please remove the dash or any special characters. Example: 991112123456</p>
            <input class="form-control" id="ic_number" name="ic_number" pattern="([A-Za-z0-9])+" required>
        </div>
        <div class="mb-3">
            <label for="role" class="col-form-label text-md-end">{{ __('Role') }}</label>
            <select id="role" name="role" class="form-select" aria-label="User role" required >
                <option value="">Please select your role</option>
                <option value="1" selected>Admin</option>
                <option value="2">Lecturer</option>
                <option value="3">Student</option>
            </select>
        </div>
        <div>
            <button class="btn btn-success" type="submit">Add User</button>
        </div>
    </form>
@endsection
