@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Lecturer Dashboard') }}</div>

                <div class="card-body">
                     @if(is_null($lecturer))
                        <p>Your account has been removed. Contact the administrator for further assisstance.</p>
                    @else
                        <h4>Welcome, {{ $lecturer->name }}!</h4>
                        <p>Your Email: {{ $lecturer->email }}</p>
                        <!-- Add more lecturer-specific content here -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection