@extends('layouts.app')

@section('content')
<div class="p-3">
<h1>Timetable</h1>

@if(auth()->check() && auth()->user()->role == '1')
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
<a href="{{ route('tt.create') }}" class="btn btn-primary mb-3">Add Timetable</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Group</th>
            <th>Day</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($timetables as $timetable)
            <tr>
                <td>{{ $timetable->group->name }}</td>
                <td>{{ $timetable->day }}</td>
                <!-- <td>{{ $timetable->start_time }}</td>
                <td>{{ $timetable->end_time }}</td> -->
                <td>{{ $timetable->date }}</td>
                <!-- <td>{{ $timetable->venue }}</td> -->
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
@endif
<table class="table table-bordered">
    <thead class="text-center">
      <tr>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col" class="col-4"  style="background-color: #c2c1c1;">Sabtu</th>  
        <th scope="col" class="col-4" style="background-color: #c2c1c1;">Ahad</th>
      </tr>
      <tr style="background-color: #c2c1c1;">
        <th scope="row">Minggu</th>
        <th scope="row">Tarikh</th>
        <!-- <td>0830-1030</td>
        <td>1100-1300</td>
        <td>1400-1600</td>
        <td>1630-1830</td>
        <td>2000-2200</td>
        <td>0830-1030</td>
        <td>1100-1300</td>
        <td>1400-1600</td>
        <td>1630-1830</td> -->
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
           
            <td id="saturday"  class="" data-bs-toggle="modal" data-bs-target="#timetableModal{{ $saturdayTimetable->id }}" style="cursor: pointer; background-color: {{ $colors[$saturdayTimetable->group->id] ?? 'white' }}; color: yellow;">GROUP {{ $saturdayTimetable->group->name }}</td>
        @else
            <td ></td>
        @endif
        @if($sundayTimetable || (isset($groupedTimetables[$nextDate]) && $groupedTimetables[$nextDate]->firstWhere('day', 'Sunday')))
            @php
                $sundayTimetable = $sundayTimetable ?? $groupedTimetables[$nextDate]->firstWhere('day', 'Sunday');
            @endphp
            <td id="sunday"  class="" data-bs-toggle="modal" data-bs-target="#timetableModal{{ $sundayTimetable->id }}" style="cursor: pointer; background-color: {{ $colors[$sundayTimetable->group->id] ?? 'white' }}; color: yellow;"> GROUP {{ $sundayTimetable->group->name }}</td>
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
  @foreach ($groupedTimetables as $date => $timetables)
    @foreach ($timetables as $timetable)
        <div class="modal fade" id="timetableModal{{ $timetable->id }}" tabindex="-1" aria-labelledby="timetableModalLabel{{ $timetable->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="timetableModalLabel{{ $timetable->id }}">Timetable Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Group:</strong> {{ $timetable->group->name }}</p>
                        <p><strong>Day:</strong> {{ $timetable->day }}</p>
                        <p><strong>Date:</strong> {{ $timetable->date }}</p>
                        <hr>
                        <h6>Subjects:</h6>
                        @if($timetable->group->subjects->count() > 0)
                            <ul>
                                @foreach($timetable->group->subjects as $subject)
                                    <li>
                                    <span class="badge">{{ $subject->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No subjects assigned to this group.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

</div>


 
      
@endsection
