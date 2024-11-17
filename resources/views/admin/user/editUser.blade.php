@extends('layouts.app')

@section('content')

<div class="container p-5">
    <button class="btn btn-secondary mb-3" type="button" onclick="window.history.back()">Back</button>

    <div class="row col-md-12">
        <div class="col-md-4">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <h4 class="mb-5">Edit User</h4>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" placeholder="" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="col-form-label text-md-end">{{ __('Role') }}</label>
                    <select id="role" name="role" class="form-select" aria-label="Default select example" required {{ ($user->role == 1 ? "disabled":"") }}>
                        <option value="">Please select your role</option>
                        @if( auth()->user()->role == 1)
                            <option value="1" selected>Admin</option>
                        @endif
                        <option value="2" {{ ($user->role == 2 ? "selected":"") }}>Lecturer</option>
                        <option value="3" {{ ($user->role == 3 ? "selected":"") }}>Student</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection