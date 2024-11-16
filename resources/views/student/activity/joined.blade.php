@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Joined Activities</h4>
        </div>
        <div class="card-body">
            <div class="container my-4">
                <!-- Search Section -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="search" class="form-label fw-bold">Search</label>
                        <input type="text" id="search" class="form-control" placeholder="Search For Activities...">
                    </div>
                </div>

                <!-- Activities Section -->
                <div class="row">
                    @foreach ($joinedActivities as $activity)
                    @php
                    $activityTypes = [
                    \App\Enums\ActivityType::Audition => 'Audition',
                    \App\Enums\ActivityType::Tryout => 'Tryout',
                    \App\Enums\ActivityType::Practice => 'Practice',
                    \App\Enums\ActivityType::Competition => 'Competition',
                    ];

                    $hasJoined = auth()
                    ->user()
                    ->joinedActivities->contains($activity->id);
                    @endphp

                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $activity->title }}</h5>
                                <p class="card-text">
                                    <strong>Posted By:</strong> {{ $activity->posted_by ?? '' }} <br>
                                    @if($activity->type == 0 && $activity->type != 3)
                                    <strong>Organization:</strong> {{ $activity->organizations->name ?? '' }} <br>
                                    @endif

                                    @if(($activity->type == 1 || $activity->type == 2) && $activity->id != 3)
                                    <strong>Sport Name:</strong> {{ $activity->sports->name ?? '' }} <br>
                                    @endif
                                    <strong>Type:</strong> {{ $activityTypes[$activity->type] ?? '' }} <br>
                                    <strong>Venue:</strong> {{ $activity->venue }} <br>
                                    <strong>Duration:</strong> {{ $activity->start_date }} -
                                    {{ $activity->end_date }}
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button class="btn btn-dark view-button" data-activity-id="{{ $activity->id }}"
                                    data-bs-toggle="modal" data-bs-target="#activityDetailsModal">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach ($practices as $activity)
                    @php
                    $activityTypes = [
                    \App\Enums\ActivityType::Audition => 'Audition',
                    \App\Enums\ActivityType::Tryout => 'Tryout',
                    \App\Enums\ActivityType::Practice => 'Practice',
                    \App\Enums\ActivityType::Competition => 'Competition',
                    ];

                    $hasJoined = auth()
                    ->user()
                    ->joinedActivities->contains($activity->id);
                    @endphp

                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $activity->activity->title }}</h5>
                                <p class="card-text">
                                    <strong>Posted By:</strong> {{ $activity->posted_by ?? '' }} <br>
                                    
                                    @if($activity->organizations)
                                    <strong>Organization: </strong> {{ $activity->organizations->name ?? '' }} <br>
                                    @else
                                    <strong>Sport Name: </strong> {{ $activity->sports->name ?? '' }} <br>
                                    @endif

                                    

                                    <strong>Type:</strong> {{ $activityTypes[$activity->activity->type] ?? '' }}
                                    <br>
                                    <strong>Venue:</strong> {{ $activity->activity->venue }} <br>
                                    <strong>Duration:</strong> {{ $activity->activity->start_date }} -
                                    {{ $activity->end_date }}
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button class="btn btn-dark view-button"
                                    data-activity-id="{{ $activity->activity->id }}" data-bs-toggle="modal"
                                    data-bs-target="#activityDetailsModal">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="activityDetailsModal" tabindex="-1" aria-labelledby="activityDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityDetailsModalLabel">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Posted By:</strong> <span id="activity-posted-by"></span></li>
                            <li id="_org" style="display: none;"><strong>Organization:</strong> <span id="activity-organization"></span></li>
                            <li id="_sport" style="display: none;"><strong>Sport Name:</strong> <span id="activity-sport-name"></span></li>
                            <li><strong>Title:</strong> <span id="activity-title"></span></li>
                            <li><strong>Target Players:</strong> <span id="activity-target-players"></span></li>
                            <li><strong>Content:</strong> <span id="activity-content"></span></li>
                            <li><strong>Activity Type:</strong> <span id="activity-type"></span></li>
                            <li><strong>Activity Duration:</strong> <span id="activity-duration"></span></li>
                            <li><strong>Venue:</strong> <span id="activity-venue"></span></li>
                            <li><strong>Address:</strong> <span id="activity-address"></span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <img id="activity-image" src="" class="img-fluid" alt="Activity Image">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(() => {

        $('#search').on('input', function() {
            var searchQuery = $(this).val().toLowerCase();

            // Loop through each activity card and check if the title matches the search query
            $('.card-title').each(function() {
                var activityTitle = $(this).text().toLowerCase();

                // If the activity title contains the search query, show it, otherwise hide it
                if (activityTitle.includes(searchQuery)) {
                    $(this).closest('.col-md-4').show(); // Show the parent card
                } else {
                    $(this).closest('.col-md-4').hide(); // Hide the parent card
                }
            });
        });

        const activityTypeMap = {
            0: 'Audition',
            1: 'Tryout',
            2: 'Practice',
            3: 'Competition'
        };

        $('.view-button').on('click', function() {
            var activityId = $(this).data('activity-id');
            var baseUrl = "{{ asset('/storage/') }}";
            $.ajax({
                url: '/activity/' + activityId,
                method: 'GET',
                success: function(data) {
                    console.log(data);

                    // Populate fields with data
                    $('#activity-sport-name').text((data.sports && data.sports.name) || '');
                    $('#activity-organization').text((data.organizations && data.organizations.name) || '');
                    $('#activity-posted-by').text((data.posted_by && data.posted_by) || '');
                    $('#activity-title').text(data.title);
                    $('#activity-target-players').text(data.target_player === 1 ?
                        'Official Players' : 'All Student');
                    $('#activity-content').text(data.content);
                    const activityTypeText = activityTypeMap[data.type] || 'Unknown';
                    $('#activity-type').text(activityTypeText);
                    $('#activity-duration').text(data.start_date + ' - ' + data.end_date);
                    $('#activity-venue').text(data.venue);
                    $('#activity-address').text(data.address);
                    $('#activity-image').attr('src', data.attachment ? baseUrl + '/' + data
                        .attachment : '/path-to-placeholder-image.jpg');

                    if (data.type == 3) {
                        $('#sports').hide();
                    } else {
                        $('#sports').show();
                    }

                    if(data.type == 0 && data.type != 3){
                        document.getElementById('_org').style.display = 'block';
                        document.getElementById('_sport').style.display = 'none';
                    }

                    if((data.type == 1 || data.type == 2) && data.type != 3){
                        document.getElementById('_sport').style.display = 'block';
                        document.getElementById('_org').style.display = 'none';
                    }
                },
                error: function(xhr) {
                    alert('Error fetching activity details.');
                }
            });

        });
    })
</script>
@endpush