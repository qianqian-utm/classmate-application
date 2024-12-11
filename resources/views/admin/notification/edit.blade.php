@extends('layouts.app')

@section('content')
<div class="container p-5">
    <button class="btn btn-secondary mb-3" type="button" onclick="window.history.back()">Back</button>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('admin.notification.update', ['type' => $type, 'id' => $notification->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <h4 class="mb-5">Edit {{ ucfirst($type) }} Notification</h4>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                            id="subject_id" name="subject_id" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('subject_id', $notification->subject_id) == $subject->id ? 'selected' : '' }}>
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
                           id="title" 
                           name="title" 
                           value="{{ old('title', $notification->title) }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="scheduled_at" class="form-label">Scheduled Date</label>
                    <input type="date" 
                           class="form-control @error('scheduled_at') is-invalid @enderror" 
                           id="scheduled_at" 
                           name="scheduled_at" 
                           value="{{ old('scheduled_at', $notification->scheduled_at ? 
                               (is_string($notification->scheduled_at) ? 
                                   $notification->scheduled_at : 
                                   $notification->scheduled_at->format('Y-m-d')) : '') }}"
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
                              required="true">{{ old('description', $notification->description) }}</textarea>
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
                        <option value="lab" {{ old('assignment_type', $notification->assignment_type) == 'lab' ? 'selected' : '' }}>Lab</option>
                        <option value="individual" {{ old('assignment_type', $notification->assignment_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="group_project" {{ old('assignment_type', $notification->assignment_type) == 'group_project' ? 'selected' : '' }}>Group Project</option>
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
                        <option value="quiz" {{ old('exam_type', $notification->exam_type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="midterm" {{ old('exam_type', $notification->exam_type) == 'midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="final" {{ old('exam_type', $notification->exam_type) == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('exam_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                @if($type != 'assignment')
                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" 
                            class="form-control @error('venue') is-invalid @enderror" 
                            id="venue" 
                            name="venue" 
                            value="{{ old('venue', $notification->venue) }}">
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div>
                    <button type="submit" class="btn btn-success">Update Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection