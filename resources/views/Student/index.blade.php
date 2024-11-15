@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Student Dashboard') }}</div>

                <div class="card-body">
                    @if(is_null($student))
                        <p>Your account has been removed. Contact the administrator for further assisstance.</p>
                    @else
                        <h4>Welcome, {{ $student->name }}!</h4>
                        <p>Your Email: {{ $student->email }}</p>
                        <!-- Add more student-specific content here -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection