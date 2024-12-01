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

    <table class="table table-bordered">
        <thead class="text-center">
          <tr>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col" colspan="5" style="background-color: #c2c1c1;">Sabtu</th>  
            <th scope="col" colspan="5"style="background-color: #c2c1c1;">Ahad</th>
          </tr>
          <tr style="background-color: #c2c1c1;">
            <th scope="row">Minggu</th>
            <th scope="row">Tarikh</th>
            <td>0830-1030</td>
            <td>1100-1300</td>
            <td>1400-1600</td>
            <td>1630-1830</td>
            <td>2000-2200</td>
            <td>0830-1030</td>
            <td>1100-1300</td>
            <td>1400-1600</td>
            <td>1630-1830</td>
          </tr>
        </thead>
        <tbody class="text-center">
        @php
    $groupedTimetables = $timetables->sortBy('date')->groupBy('date');
    $combinedDates = [];
    $weekCounter = 1; // Initialize a counter for the weeks
@endphp

@foreach ($groupedTimetables as $date => $timetables)
    @php
        $saturdayTimetable = $timetables->firstWhere('day', 'Saturday');
        $sundayTimetable = $timetables->firstWhere('day', 'Sunday');
        $colors = [
            1 => '#b02bb0',
            2 => '#06aff0',
            3 => '#08bc05'
        ];
        $nextDate = \Carbon\Carbon::parse($date)->addDay()->toDateString();
        $displayDate = \Carbon\Carbon::parse($date)->format('j M');

        if (isset($groupedTimetables[$nextDate])) {
            $displayDate = \Carbon\Carbon::parse($date)->format('j M') . ' - ' . \Carbon\Carbon::parse($nextDate)->format('j M');
            $combinedDates[] = $nextDate;
        }
    @endphp

    @if (!in_array($date, $combinedDates))
        <tr>
            <td>Week {{ $weekCounter }}</td> <!-- Use the dynamic week counter -->
            <td>{{ $displayDate }}</td>
            @if($saturdayTimetable)
                <td colspan="2"></td>
                <td id="saturday" colspan="3" class="" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor: pointer; background-color: {{ $colors[$saturdayTimetable->group->id] ?? 'white' }};">{{ $saturdayTimetable->group->name }}</td>
            @else
                <td colspan="5"></td>
            @endif
            @if($sundayTimetable || (isset($groupedTimetables[$nextDate]) && $groupedTimetables[$nextDate]->firstWhere('day', 'Sunday')))
                @php
                    $sundayTimetable = $sundayTimetable ?? $groupedTimetables[$nextDate]->firstWhere('day', 'Sunday');
                @endphp
                <td id="sunday" colspan="3" class="" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor: pointer; background-color: {{ $colors[$sundayTimetable->group->id] ?? 'white' }};">{{ $sundayTimetable->group->name }}</td>
            @endif
        </tr>
        @php
            $weekCounter++; // Increment the week counter for the next iteration
        @endphp
    @endif
@endforeach

          <!-- <tr>
            <td></td>
            <td>13 Jan - 30 Jan</td>
            <td colspan="9" style="background-color: #c2c1c1;">Minggu Ulang kaji</td>      
          </tr>
          <tr>
            <td></td>
            <td>30 Jan - 16 Feb</td>
            <td colspan="9" style="background-color: #fffe0c;">Minggu Peperiksaan</td>      
          </tr> -->
        </tbody>

      

      </table>
      
@endsection
