@component('mail::message')
# New {{ $informationDetail->type }} {{ $action }}

A new {{ $informationDetail->type }} has been {{ $action }} for {{ $informationDetail->subject->name }}.

**Title:** {{ $informationDetail->title }} <br>
**Description:** {{ $informationDetail->description }} <br>
@if($informationDetail->venue)
**Venue:** {{ $informationDetail->venue }} <br>
@endif
**Scheduled at:** {{ $informationDetail->scheduled_at->format('d M Y') }} <br>

Login to view details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent