@extends('layouts.app')

@section('content')
<div class="p-5 col-6">
    <h1>Add Timetable</h1>

    <form action="{{ route('tt.store') }}" method="POST">
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
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" id="date">
        </div>
        <div class="form-group">
            <label for="day">Day</label>
            <input type="text" name="day" class="form-control" id="day" readonly>
        </div>
        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('date');
        const daySelect = document.getElementById('day');

        dateInput.addEventListener('change', function () {
            const selectedDate = new Date(dateInput.value);
            const dayOfWeek = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                // Update the dropdown based on the selected date
                const selectedDay = dayOfWeek === 0 ? "Sunday" : "Saturday";
                daySelect.value = selectedDay;
            } else {
                alert("Please select a Saturday or Sunday.");
                dateInput.value = ""; // Clear invalid input
            }
        });
    });
</script>
@endsection
