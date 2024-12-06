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
                    {{ __('Subject Management') }}
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create Subject
                    </a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('subjects.index') }}" class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Search by code, name, or lecturer" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="group" class="form-control">
                                    <option value="">All Groups</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                                <a href="{{ route('subjects.index') }}" class="btn btn-secondary flex-grow-1">Reset</a>
                            </div>
                        </div>
                    </form>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Groups</th>
                                <th>Lecturer(s)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                            <tr>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>
                                    @foreach($subject->groups as $group)
                                        <span class="badge bg-primary">{{ $group->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @forelse($subject->users->where('role', 2) as $user)
                                        <span class="badge bg-secondary">{{ $user->name }}</span>
                                    @empty
                                        <span class="badge bg-warning">No Lecturers</span>
                                    @endforelse
                                </td>
                                <td>
                                    <a href="{{ route('subjects.show', $subject) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this subject?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        @if ($subjects->lastPage() > 1)
                            <div class="w3-bar w3-center">
                                {{-- Previous Page Link --}}
                                @if ($subjects->currentPage() > 1)
                                    <a href="{{ $subjects->appends(request()->input())->previousPageUrl() }}" class="w3-button w3-hover-light-grey">&laquo; Previous</a>
                                @endif

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $subjects->lastPage(); $i++)
                                    <a href="{{ $subjects->appends(request()->input())->url($i) }}" 
                                    class="w3-button {{ ($subjects->currentPage() == $i) ? 'w3-blue' : 'w3-hover-light-grey' }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($subjects->currentPage() < $subjects->lastPage())
                                    <a href="{{ $subjects->appends(request()->input())->nextPageUrl() }}" class="w3-button w3-hover-light-grey">Next &raquo;</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection