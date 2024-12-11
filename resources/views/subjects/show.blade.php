@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Subjects
            </a>
            <strong>{{ $subject->code }} {{ $subject->name }}</strong>
            @forelse($subject->groups as $group)
                <span class="badge bg-primary me-1">{{ $group->name }}</span>
            @empty
                <p class="text-muted">No groups assigned</p>
            @endforelse
            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-pencil"></i> Edit
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('subjects.show', $subject) }}" class="mb-3">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ $searchTerm ?? request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="role" class="form-control">
                            <option value="">All Roles</option>
                            <option value="2" {{ $roleFilter === '2' ? 'selected' : '' }}>Lecturer</option>
                            <option value="3" {{ $roleFilter === '3' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-secondary flex-grow-1">Reset</a>
                    </div>
                </div>
            </form>

            @if($users->isEmpty())
                <h5 class="text-muted">No users assigned</h5>
            @else
                <div class="row">
                    <div class="col-12">
                        <table class="table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            @include('partials.pagination', ['records' => $users])
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const table = document.getElementById('usersTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const roleFilterValue = roleFilter.value.toLowerCase();

        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();

            const nameMatch = name.includes(searchTerm);
            const emailMatch = email.includes(searchTerm);
            const roleMatch = roleFilterValue === '' || role === roleFilterValue;

            row.style.display = (searchTerm === '' || nameMatch || emailMatch) && roleMatch ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection