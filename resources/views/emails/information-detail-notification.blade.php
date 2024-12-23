@component('mail::message')
# New {{ $informationDetail->type }} {{ $action }}

A new {{ $informationDetail->type }} has been {{ $action }} for {{ $informationDetail->subject->name }}.

**Title:** {{ $informationDetail->title }}
**Description:** {{ $informationDetail->description }}
@if($informationDetail->venue)
**Venue:** {{ $informationDetail->venue }}
@endif
**Scheduled at:** {{ $informationDetail->scheduled_at->format('d M Y') }}

@component('mail::button', ['url' => route('information.show', $informationDetail->id)])
View Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent