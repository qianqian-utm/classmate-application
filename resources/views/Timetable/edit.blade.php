@extends('layouts.app')

@section('content')
<div class="p-3">
<h1>Edit Timetable</h1>

<div class="col-5">
<form action="{{ route('tt.update', $timetable->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
<label for="group_id">Group</label>
<select name="group_id" class="form-control">
    @foreach ($groups as $group)
        <!-- Check if the group's ID matches the selected group_id -->
        <option value="{{ $group->id }}" {{ $group->id == $timetable->group_id ? 'selected' : '' }}>
            {{ $group->name }}
        </option>
    @endforeach
</select>
</div>

    <div class="form-group">
<label for="day">Day</label>
<select name="day" class="form-control">
    <!-- Display the current value as the selected option -->
    <option value="{{ $timetable->day }}" selected>{{ $timetable->day }}</option>
    
    <!-- Only display other days if they are not the same as $timetable->day -->
    @if ($timetable->day !== 'Saturday')
        <option value="Saturday">Saturday</option>
    @endif
    @if ($timetable->day !== 'Sunday')
        <option value="Sunday">Sunday</option>
    @endif
</select>
</div>
    <!-- <div class="form-group">
        <label for="start_time">Start Time</label>
        <input type="time" name="start_time" value="{{ $timetable->start_time }}" class="form-control">
    </div>
    <div class="form-group">
        <label for="end_time">End Time</label>
        <input type="time" name="end_time" value="{{ $timetable->end_time }}" class="form-control">
    </div> -->
    <div class="form-group">
        <label for="end_time">Date</label>
        <input type="date" name="date" value="{{ $timetable->date }}" class="form-control">
    </div>
    <!-- <div class="form-group">
        <label for="venue">Venue</label>
        <input type="text" name="venue" class="form-control" value="{{ $timetable->venue }}">
    </div> -->
    <button type="submit" class="btn btn-success mt-3">Save</button>
</form>
</div>

</div>
  
@endsection
