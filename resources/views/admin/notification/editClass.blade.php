@extends('layouts.app')

@section('content')

<div class="container p-5">
<button class="btn btn-secondary mb-3" type="button" onclick="window.history.back()">Back</button>

    <div class="row col-md-12">
        <div class="col-md-4">
            <form action="{{ route('admin.notification.class.update', $classDetail->id) }}" method="POST">
    @csrf
    @method('PUT')
            <h4 class="mb-5">Edit Class Notification</h4>
            <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Choose Subject</label>
            <select class="form-select" name="subject_id" required>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $subject->id == $classDetail->subject_id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" id="title" value="{{ $classDetail->date }}" placeholder="">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $classDetail->title }}" placeholder="">
            </div>
            <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ $classDetail->description }}</textarea>
            </div>
            <div>
                <button class="btn btn-success" type="submit">Update</button>
            </div>

            </form>
            
        </div>
</div>
@endsection