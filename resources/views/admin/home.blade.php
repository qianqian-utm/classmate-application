@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Dashboard') }}
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('home') }}" class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="role" class="form-control">
                                    <option value="">All Roles</option>
                                    <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                                    <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Lecturer</option>
                                    <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Student</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                                <a href="{{ route('home') }}" class="btn btn-secondary flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </form>

                    <table class="table">
                        <thead> 
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th colspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 2) Lecturer 
                                        @elseif($user->role == 3) Student 
                                        @else Admin 
                                        @endif
                                    </td>
                                    <td>
                                        <button type="submit" onclick="window.location.href='{{ route('user.edit' , $user->id) }}'" class="btn btn-warning btn-sm" >
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST" style="display: inline;" id="deleteForm{{ $user->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="confirmDelete({{ $user->id }})" 
                                                    {{ $user->role == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        @if ($users->lastPage() > 1)
                            <div class="w3-bar w3-center">
                                {{-- Previous Page Link --}}
                                @if ($users->currentPage() > 1)
                                    <a href="{{ $users->appends(request()->input())->previousPageUrl() }}" class="w3-button w3-hover-light-grey">&laquo; Previous</a>
                                @endif

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <a href="{{ $users->appends(request()->input())->url($i) }}" 
                                    class="w3-button {{ ($users->currentPage() == $i) ? 'w3-blue' : 'w3-hover-light-grey' }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($users->currentPage() < $users->lastPage())
                                    <a href="{{ $users->appends(request()->input())->nextPageUrl() }}" class="w3-button w3-hover-light-grey">Next &raquo;</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        document.getElementById('deleteForm' + userId).submit();
    }
}
</script>
@endpush
@endsection