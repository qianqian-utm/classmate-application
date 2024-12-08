@extends('layouts.app')

@section('content')
<div class="container p-5">
    <button class="btn btn-secondary mb-3" type="button" onclick="window.history.back()">Back</button>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('admin.notification.store', ['type' => $type]) }}" method="POST">
                @csrf
                <h4 class="mb-5">Create {{ ucfirst($type) }} Notification</h4>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Choose Subject</label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                            name="subject_id" id="subject_id" required>
                        <option value="">Select a Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="scheduled_at" class="form-label">Date</label>
                    <input type="date" 
                           class="form-control @error('scheduled_at') is-invalid @enderror" 
                           name="scheduled_at" 
                           id="scheduled_at" 
                           value="{{ old('scheduled_at') }}"
                           required="true">
                    @error('scheduled_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3"
                              required="true">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($type == 'assignment')
                <div class="mb-3">
                    <label for="assignment_type" class="form-label">Assignment Type</label>
                    <select class="form-select @error('assignment_type') is-invalid @enderror" 
                            id="assignment_type" 
                            name="assignment_type" 
                            required>
                        <option value="">Select Assignment Type</option>
                        <option value="lab" {{ old('assignment_type') == 'lab' ? 'selected' : '' }}>Lab</option>
                        <option value="individual" {{ old('assignment_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="group_project" {{ old('assignment_type') == 'group_project' ? 'selected' : '' }}>Group Project</option>
                    </select>
                    @error('assignment_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                @if($type == 'exam')
                <div class="mb-3">
                    <label for="exam_type" class="form-label">Exam Type</label>
                    <select class="form-select @error('exam_type') is-invalid @enderror" 
                            id="exam_type" 
                            name="exam_type" 
                            required>
                        <option value="">Select Exam Type</option>
                        <option value="quiz" {{ old('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="midterm" {{ old('exam_type') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="final" {{ old('exam_type') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('exam_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                <div class="mb-3">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" 
                           class="form-control @error('venue') is-invalid @enderror" 
                           id="venue" 
                           name="venue" 
                           value="{{ old('venue') }}">
                    @error('venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <button class="btn btn-success" type="submit">Create Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection