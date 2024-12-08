@extends('layouts.app')

@section('content')
<div class="container p-5">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="mb-3">Notifications</h2>

    {{-- Subject Filter --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="subject-filter" class="form-control">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row col-md-12">
<<<<<<< HEAD
        <div class="card col-md-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Class</h4>
                    @if(auth()->check() && auth()->user()->role == '1')
                        <button class="btn btn-success" onclick="window.location.href='{{ route('admin.notification.class') }}'"  type="button"><i class="fas fa-add"></i> Add</button>
                    @endif
                </div>
                    <div class="" style="max-height: 550px; overflow-y:scroll;">
                        @foreach($classDetails as $classDetail)
                            <div class="mb-4" >
                                <div class="card bg-white" style="">
=======
        @foreach(['class', 'exam', 'assignment'] as $type)
            <div class="card col-md-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>{{ ucfirst($type) }}</h4>
                        @if(auth()->check() && auth()->user()->role == '1')
                        <a href="{{ route('admin.notification.create', ['type' => $type]) }}" class="btn btn-success">
                            <i class="fas fa-add"></i> Add
                        </a>
                        @endif
                    </div>
                    <div class="" style="max-height: 550px; overflow-y:auto;">
                        @foreach($notifications[$type] as $notification)
                            <div class="mb-4 notification-item" 
                                 data-subject-id="{{ $notification->subject_id }}"
                                 data-type="{{ $type }}">
                                <div class="card bg-white" style="max-height: 200px; overflow-y:auto">
>>>>>>> d844db2ac5fbc3310e6158de9d78867045ab940a
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                                <div>
                                                @if(auth()->check() && auth()->user()->role == '1')
                                                    <a href="{{ route('admin.notification.edit', ['type' => $type, 'id' => $notification->id]) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.notification.delete', ['type' => $type, 'id' => $notification->id]) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <h5>
                                            {{ $notification->title }}
                                        </h5>
<<<<<<< HEAD
                                        <strong>{{ $classDetail->title }}</strong>
                                        <div>
                                    <textarea class="form-control" name="" id="" disabled><p>{{ $classDetail->description }}</p></textarea>

                                    </div>
=======
                                        <p>{{ $notification->description }}</p>
                                        
                                        <p>
                                            @if($notification->scheduled_at)
                                            <strong>
                                                @switch($type)
                                                    @case('assignment')
                                                        Due date:
                                                        @break
                                                    @case('class')
                                                        Class date:
                                                        @break
                                                    @case('exam')
                                                        Exam date:
                                                        @break
                                                @endswitch

                                                {{ \Carbon\Carbon::parse($notification->scheduled_at)->format('d F Y') }}
                                            </strong>
                                            @endif
                                        </p>
                                        <span class="badge">{{ $notification->subject->name }}</span>

                                        @if($type == 'assignment' && $notification->assignment_type)
                                            <span class="badge badge-{{$notification->assignment_type}}">{{ ucfirst(str_replace('_', ' ', $notification->assignment_type)) }}</span>
                                        @endif
                                        
                                        @if($type == 'exam' && $notification->exam_type)
                                            <span class="badge badge-{{$notification->exam_type}}">{{ ucfirst($notification->exam_type) }}</span>
                                        @endif
>>>>>>> d844db2ac5fbc3310e6158de9d78867045ab940a
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
<<<<<<< HEAD
            </div>
        </div>

        <div class="card col-md-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Exam</h4>
                    @if(auth()->check() && auth()->user()->role == '1')
                        <button class="btn btn-success" onclick="window.location.href='{{ route('admin.notification.exam') }}'"  type="button"><i class="fas fa-add"></i> Add</button>
                    @endif
                </div>
                <div class="" style="max-height: 550px; overflow-y:scroll;">
                    @foreach($examDetails as $examDetail)
                        <div class="mb-4" >
                            <div class="card bg-white" style="">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between" >
                                        <p>{{ \Carbon\Carbon::parse($examDetail->created_at)->format('j F Y') }}</p>
                                        <div>
                                            @if(auth()->check() && auth()->user()->role == '1')
                                                <button type="submit" onclick="window.location.href='{{ route('admin.notification.exam.edit' , $examDetail->id) }}'" class="btn btn-warning btn-sm" >
                                                    <i class="fas fa-pencil"></i>
                                                </button>
                                                <form action="{{ route('admin.notification.exam.delete', $examDetail->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <strong>{{ $examDetail->title }}</strong>
                                    <div>
                                    <textarea class="form-control" name="" id="" disabled><p>{{ $examDetail->description }}</p></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card col-md-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Assignment</h4>
                @if(auth()->check() && auth()->user()->role == '2')
                <button class="btn btn-success" onclick="window.location.href='{{ route('admin.notification.assignment') }}'"  type="button"><i class="fas fa-add"></i> Add</button>
                @endif
                </div>
                <div class="" style="max-height: 550px; overflow-y:scroll;">
                    @foreach($assignmentDetails as $data)
                        <div class="mb-4" >
                            <div class="card bg-white" style="">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between" >
                                        <p>{{ \Carbon\Carbon::parse($classDetail->created_at)->format('j F Y') }}</p>
                                        <div>
                                            @if(auth()->check() && auth()->user()->role == '2')
                                                <button type="submit" onclick="window.location.href='{{ route('admin.notification.assignment.edit' , $data->id) }}'" class="btn btn-warning btn-sm" >
                                                    <i class="fas fa-pencil"></i>
                                                </button>
                                                <form action="{{ route('admin.notification.assignment.delete', $data->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <h5>{{ $data->subject->code }} {{ $data->subject->name }} on {{ \Carbon\Carbon::parse($data->due_date)->format('d F Y') }}
                                    </h5>
                                    <strong>{{ $data->title }}</strong>
                                    <div>
                                    <textarea class="form-control" name="" id="" disabled><p>{{ $data->description }}</p></textarea>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
=======
                </div>
            </div>
        @endforeach
    </div>
>>>>>>> d844db2ac5fbc3310e6158de9d78867045ab940a
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectFilter = document.getElementById('subject-filter');
    const notificationItems = document.querySelectorAll('.notification-item');

    subjectFilter.addEventListener('change', function() {
        const selectedSubjectId = this.value;

        notificationItems.forEach(item => {
            const itemSubjectId = item.getAttribute('data-subject-id');
            
            if (selectedSubjectId === '' || itemSubjectId === selectedSubjectId) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection