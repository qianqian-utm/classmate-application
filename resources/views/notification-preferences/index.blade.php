@extends('layouts.app')

@section('content')
<div class="container p-5">
    <h2 class="mb-3">Notification Preferences</h2>
    <div class="row col-md-12">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if($subjects->isEmpty())
                <div class="alert alert-warning mt-4" role="alert">
                    You are currently not enrolled in any subject.
                </div>
        @else
            <form method="POST" action="{{ route('notification-preferences.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                            name="global_email_enabled" 
                            id="global_email_enabled"
                            value="1"
                            {{ $user->global_email_enabled ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Subscribe to email updates?</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1">
                        If subscribed, you will receive email notifications for your selected subjects when there is new/updated information.
                    </p>
                </div>

                <div class="border-t pt-4 mt-4">
                    <div class="flex items-center mb-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                id="select_all"
                                class="form-checkbox h-5 w-5 text-blue-600"
                                {{ !$user->global_email_enabled ? 'disabled' : '' }}>
                            <span class="ml-2 text-gray-700 font-medium">Select/Deselect All Subjects</span>
                        </label>
                    </div>

                    <div class="space-y-4">
                        @foreach($subjects as $subject)
                            <div class="flex items-center justify-between border-b pb-2">
                                <label for="pref_{{ $subject->id }}" class="text-gray-700">
                                    <div>{{ $subject->code }} {{ $subject->name }}</div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                        name="preferences[{{ $subject->id }}]"
                                        id="pref_{{ $subject->id }}"
                                        value="1"
                                        {{ $preferences[$subject->id] ?? true ? 'checked' : '' }}
                                        {{ !$user->global_email_enabled ? 'disabled' : '' }}
                                        class="form-checkbox h-5 w-5 text-blue-600 subject-checkbox">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save Preferences
                        </button>
                    </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const globalEmailEnabled = document.getElementById('global_email_enabled');
    const selectAll = document.getElementById('select_all');
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');

    // Check select all if all subjects are checked initially
    const allChecked = Array.from(subjectCheckboxes)
        .every(cb => cb.checked || cb.disabled);
    selectAll.checked = allChecked;

    globalEmailEnabled.addEventListener('change', function() {
        const isChecked = this.checked;
        selectAll.disabled = !isChecked;
        
        subjectCheckboxes.forEach(checkbox => {
            checkbox.disabled = !isChecked;
            if (isChecked) {
                checkbox.checked = true;
            }
        });
        
        if (isChecked) {
            selectAll.checked = true;
        }
    });

    selectAll.addEventListener('change', function() {
        const isChecked = this.checked;
        subjectCheckboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = isChecked;
            }
        });
    });

    subjectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(subjectCheckboxes)
                .every(cb => cb.checked || cb.disabled);
            selectAll.checked = allChecked;
        });
    });
});
</script>
@endsection