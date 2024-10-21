@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Calendar of Activity</h4>
            </div>
            <div class="card-body">
                <div class="" id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        // Function to generate a random color in hex format
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        const calendarOfActivity = $('#calendar')[0];

        // Map through activities and dynamically generate colors
        const events = {!! json_encode($activities) !!}.map(activity => {
            const backgroundColor = getRandomColor();
            const borderColor = backgroundColor; // Set border color to match background

            return {
                title: activity.title,
                start: activity.start,
                end: activity.end,
                backgroundColor: backgroundColor, // Use dynamic random color
                borderColor: borderColor,
                textColor: '#ffffff', // White text for contrast
                allDay: true // Assuming all events are full-day
            };
        });

        const calendar = new FullCalendar.Calendar(calendarOfActivity, {
            initialView: 'dayGridMonth',
            events: events, // Assign dynamic events with colors to the calendar
        });

        calendar.render();
    </script>
@endpush
