@extends('layouts.app')

@section('content')
    <h1>Timetable</h1>

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
                        <form  method="POST" style="display:inline-block;">
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
