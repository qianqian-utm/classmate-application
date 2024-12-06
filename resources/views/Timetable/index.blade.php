@extends('layouts.app')

@section('content')
    <h1>Timetable</h1>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <a href="{{ route('tt.create') }}" class="btn btn-primary">Add Timetable</a>

    <table class="table">
        <thead>
            <tr>
                <th>Group</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timetables as $timetable)
                <tr>
                    <td>{{ $timetable->group->name }}</td>
                    <td>{{ $timetable->day }}</td>
                    <td>{{ $timetable->start_time }}</td>
                    <td>{{ $timetable->end_time }}</td>
                    <td>{{ $timetable->date }}</td>
                    <td>{{ $timetable->venue }}</td>
                    <td>
                        <a href="{{ route('tt.edit', $timetable->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('tt.delete', $timetable->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
