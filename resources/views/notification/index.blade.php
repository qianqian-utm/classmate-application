@extends('layouts.app')

@section('content')
<div class="container p-5">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="mb-3">Notifications</h2>
    @if(count($subjects))
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
            @foreach(['class', 'exam', 'assignment'] as $type)
                <div class="card col-md-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>{{ ucfirst($type) }}</h4>
                            @if(auth()->check() && in_array(auth()->user()->role,['1','2']) )
                                <a href="{{ route('notification.create', ['type' => $type]) }}" class="btn btn-success">
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
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                    <div>
                                                     @if(auth()->check() && in_array(auth()->user()->role,['1','2']) )
                                                        <a href="{{ route('notification.edit', ['type' => $type, 'id' => $notification->id]) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('notification.delete', ['type' => $type, 'id' => $notification->id]) }}" method="POST" style="display: inline;" id="deleteForm{{ $notification->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm" 
                                                                    onclick="confirmDelete({{ $notification->id }})" >
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                            <h5>
                                                {{ $notification->title }}
                                            </h5>
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <h5>You are not assigned to any classes.</h5>
    @endif
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

function confirmDelete(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        document.getElementById('deleteForm' + notificationId).submit();
    }
}
</script>
@endpush
@endsection