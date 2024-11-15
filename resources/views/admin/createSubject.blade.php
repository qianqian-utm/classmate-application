@extends('layouts.app')

@section('content')
<div class="container p-5">
<h2 class="mb-3">Subject creation</h2>
    <div class="row col-md-12">
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <form action="{{ route('admin.storeSubject') }}" method="POST">
    @csrf
    @method('POST')
            <h4 class="mb-5">Create Subject</h4>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Subject Code</label>
                <input type="text" class="form-control" name="code" id="exampleFormControlInput1" placeholder="">
            </div>
            <div class="mb-5">
                <label for="exampleFormControlInput1" class="form-label">Subject Name</label>
                <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="">
            </div>
            <div>
                <button class="btn btn-success" type="submit">Create</button>
            </div>

            </form>
                </div>
            </div>
            
        </div>

        <div class="col-md-6">

<h4 class="mb-3">List of Subjects</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>Code</td>
            <td>Name</td>
        </tr>
    </thead>
    <tbody>
        @foreach($subjects as $subject)
        <tr>
            <td>{{ $subject->code }}</td>
            <td>{{ $subject->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
       
</div>
        
    </div>
</div>
@endsection