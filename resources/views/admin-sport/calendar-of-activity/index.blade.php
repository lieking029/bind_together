@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Calendar of Activity</h4>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal for Event Details -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="eventDetails"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Include Bootstrap JS -->

    <script>
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
                date_joining: activity.date_joining ?? null,
                end: activity.end,
                start_time: activity.start_time,
                end_time: activity.end_time,
                venue: activity.venue,
                address: activity.address,
                act_start: activity.act_start,
                act_end: activity.act_end,
                backgroundColor: backgroundColor, // Use consistent color
                borderColor: borderColor,
                textColor: '#ffffff', // White text for contrast
                allDay: true // Assuming all events are full-day
            };
        });

        const calendar = new FullCalendar.Calendar(calendarOfActivity, {
            initialView: 'dayGridMonth',
            events: events, // Assign dynamic events with consistent colors to the calendar
            eventClick: function(info) {
                const eventDetails = info.event;
                console.log(info.event)

                // Create a date object and format it for display
                const startDate = new Date(eventDetails.start);
                const endDate = eventDetails.end ? new Date(eventDetails.end) : 'N/A';

                // Format date and time
                const formattedStart = startDate.toLocaleString(); // Use default locale and options for user-friendly format
                const formattedEnd = endDate !== 'N/A' ? endDate.toLocaleString() : 'N/A';

                // Prepare the HTML for the modal
                let detailsHtml = null;
                if (eventDetails.extendedProps.date_joining !== null) {  // Use extendedProps to access custom properties
                    detailsHtml = `
                        Title: ${eventDetails.title}<br>
                        Venue: ${eventDetails.extendedProps.venue}<br>
                        Address: ${eventDetails.extendedProps.address}<br>
                        Duration: ${new Date(eventDetails.extendedProps.act_start).toLocaleString()} - 
                        ${new Date(eventDetails.extendedProps.act_end).toLocaleString()}<br>
                        Date Joining: ${new Date(eventDetails.extendedProps.date_joining).toLocaleDateString()}
                    `;
                } else {
                    detailsHtml = `Title: ${eventDetails.title}<br>Start: ${formattedStart}<br>End: ${formattedEnd}`;
                }
                // Set the details in the modal
                document.getElementById('eventDetails').innerHTML = detailsHtml;

                // Show the modal
                const myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                myModal.show();
            }
        });

        calendar.render();
    </script>
@endpush
