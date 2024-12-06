@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Subject</div>

                <div class="card-body">
                    <form action="{{ route('subjects.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Subject Code</label>
                            <input type="text" class="form-control" name="code" placeholder="Enter subject code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter subject name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign Groups</label>
                            <select name="groups[]" class="form-control" required>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign Lecturers</label>
                            <select name="users[]" class="form-control" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Subject</button>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection