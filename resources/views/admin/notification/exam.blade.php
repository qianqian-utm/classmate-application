@extends('layouts.app')

@section('content')

<div class="container p-5">
<button class="btn btn-secondary mb-3" type="button" onclick="window.history.back()">Back</button>

    <div class="row col-md-12">
        <div class="col-md-4">
            <form action="{{ route('admin.notification.exam.store') }}" method="POST">
    @csrf
            <h4 class="mb-5">Create Exam Notification</h4>
            
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="">
            </div>
            
            <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"></textarea>
            </div>
            <div>
                <button class="btn btn-success" type="submit">Create</button>
            </div>

            </form>
            
        </div>
</div>
@endsection