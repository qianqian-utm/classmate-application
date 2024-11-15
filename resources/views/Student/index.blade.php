@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('student Dashboard') }}</div>

                <div class="card-body">
                    <h4>Welcome, {{ $student->name }}!</h4>
                    <p>Your Email: {{ $student->email }}</p>
                    <!-- Add more lecturer-specific content here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection