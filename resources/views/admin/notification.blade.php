@extends('layouts.app')

@section('content')
<div class="container p-5">
    @if(session('success'))
        <div class="alert alert-success w-25" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="mb-3">Notifications</h2>
    <div class="row col-md-12">
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
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between" >
                                            <p>{{ \Carbon\Carbon::parse($classDetail->created_at)->format('j F Y') }}</p>
                                            <div>
                                                @if(auth()->check() && auth()->user()->role == '1')
                                                    <button type="submit" onclick="window.location.href='{{ route('admin.notification.class.edit' , $classDetail->id) }}'" class="btn btn-warning btn-sm" >
                                                        <i class="fas fa-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.notification.class.delete', $classDetail->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <h5>
                                            {{ $classDetail->subject->code }} {{ $classDetail->subject->name }} on {{ \Carbon\Carbon::parse($classDetail->date)->format('d F Y') }}
                                        </h5>
                                        <strong>{{ $classDetail->title }}</strong>
                                        <div>
                                    <textarea class="form-control" name="" id="" disabled><p>{{ $classDetail->description }}</p></textarea>

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
</div>
@endsection