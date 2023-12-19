@props(['event']);

<x-event>
    <div class="card card-1">
        <div class="card--data">
            <div class="card--content">
                <img src="{{ asset('images/conference-img.jpg') }}" alt="" class="card--img">
                <h1 class="card--title">{{ $event->title }}</h1>
                <h5>ATTENDEES</h5>
                <h1>{{ $event->attendees_count }} / {{ $event->capacity }}</h1>
            </div>
        </div>
        <div class="card--stats">
            <span><i class="ri-calendar-2-fill card--icon stat--icon"></i>DD/MM/YYYY</span>
            <span><i class="ri-time-fill card--icon stat--icon"></i>HH:MM</span>
            <span><i class="ri-map-pin-2-fill card--icon map--pin"></i>{{ $event->location }}</span>
        </div>
    </div>
</x-event>
