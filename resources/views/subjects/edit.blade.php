@extends('layouts.app')

@section('content')
<div class="container p-5">
    <h2 class="mb-3">Edit Subject</h2>
    <div class="row col-md-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h4 class="mb-5">Update Subject</h4>
                        <div class="mb-3">
                            <label class="form-label">Subject Code</label>
                            <input type="text" class="form-control" name="code" value="{{ $subject->code }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $subject->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign Groups</label>
                            <select name="groups[]" class="form-control" multiple="multiple">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" 
                                        {{ in_array($group->id, $selectedGroups) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign Users</label>
                            <select name="users[]" class="form-control" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Update Subject</button>
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection