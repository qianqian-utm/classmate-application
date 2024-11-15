@extends('layouts.app')

@section('content')
    <h1>Add Timetable</h1>

    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="group_id">Group</label>
            <select name="group_id" class="form-control">
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="day">Day</label>
            <select name="day" class="form-control">
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" value="{{ $timetable->start_time }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" value="{{ $timetable->end_time }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" name="venue" class="form-control" value="{{ $timetable->venue }}">
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection
