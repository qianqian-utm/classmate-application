@extends('layouts.app')

@section('content')
<div class="container p-5">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="mb-3">Notificationss</h2>
    <div class="row col-md-12">
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
                            <div class="mb-4">
                                <div class="card bg-white" style="max-height: 200px; overflow-y:auto">
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
</div>
@endsection