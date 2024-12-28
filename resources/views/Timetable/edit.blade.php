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
                <label for="date">Date</label>
                <input type="date" name="date" value="{{ $timetable->date }}" class="form-control" id="date">
            </div>
            <div class="form-group">
                <label for="day">Day</label>
                <input type="text" name="day" class="form-control" id="day" readonly>
            </div>
            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('date');
        const daySelect = document.getElementById('day');

        updateDayValue();

        dateInput.addEventListener('change', function () {
            updateDayValue();
        });

        function updateDayValue(){
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
        }
    });
</script>
@endsection
