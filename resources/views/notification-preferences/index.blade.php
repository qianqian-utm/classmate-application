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
        <p>Subscribe to email? If subscribed, you will receive an email whenever there are new or updated information for the related subjects.</p>
        <form method="POST" action="{{ route('notification-preferences.update') }}">
            @csrf
            @method('PUT')

            <!-- update to make it checkboxes instead -->
            <div class="space-y-4">
                @foreach($subjects as $subject)
                    <div class="flex items-center justify-between border-b pb-2">
                        <label for="pref_{{ $subject->id }}" class="text-gray-700">
                            <div>{{ $subject->code }} - {{ $subject->name }}</div>
                            <div class="text-sm text-gray-500">
                                @foreach($subject->lecturers as $lecturer)
                                    <span class="bg-gray-200 rounded px-2 py-0.5 text-xs">{{ $lecturer->name }}</span>
                                @endforeach
                            </div>
                        </label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                    name="preferences[{{ $subject->id }}]"
                                    id="pref_{{ $subject->id }}"
                                    value="1"
                                    {{ $preferences[$subject->id] ?? true ? 'checked' : '' }}
                                    class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                @endforeach
            </div>

            @if($subjects->isEmpty())
                <div class="alert alert-warning" role="alert">
                    You are currently not enrolled to any subject.
                </div>
            @else
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Preferences
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection