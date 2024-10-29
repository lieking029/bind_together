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
        // Function to generate a color based on a unique string (like title or ID)
        function stringToColor(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            let color = '#';
            for (let i = 0; i < 3; i++) {
                const value = (hash >> (i * 8)) & 0xFF;
                color += ('00' + value.toString(16)).slice(-2); // Ensure two digits
            }
            return color;
        }

        const calendarOfActivity = $('#calendar')[0];

        // Map through activities and assign consistent colors based on activity title or id
        const activities = {!! json_encode($activities) !!}; // This is the object

        // Convert the object values to an array and map over them
        const events = Object.values(activities).map(activity => {
            const backgroundColor = stringToColor(activity.title); // Use title or ID to generate a consistent color
            const borderColor = backgroundColor; // Set border color to match background

            return {
                title: activity.title,
                start: activity.start,
                end: activity.end,
                backgroundColor: backgroundColor, // Use consistent color
                borderColor: borderColor,
                textColor: '#ffffff', // White text for contrast
                allDay: true // Assuming all events are full-day
            };
        });

        const calendar = new FullCalendar.Calendar(calendarOfActivity, {
            initialView: 'dayGridMonth',
            events: events, // Assign dynamic events with consistent colors to the calendar
        });

        calendar.render();
    </script>
@endpush
