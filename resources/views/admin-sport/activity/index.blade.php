@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4>Activity</h4>
                </div>
                <div class="col text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCompetitionModal">Add
                        New</button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Venue</th>
                                <th>Address</th>
                                <th>Target Audience</th>
                                <th>Activity Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $activityTypes = [
                                    \App\Enums\ActivityType::Audition => 'Audition',
                                    \App\Enums\ActivityType::Tryout => 'Tryout',
                                    \App\Enums\ActivityType::Practice => 'Practice',
                                    \App\Enums\ActivityType::Competition => 'Competition',
                                ];
                            @endphp
                            @foreach ($activities as $activity)
                                <tr>
                                    <td>{{ $activity->title }}</td>
                                    <td>{{ $activityTypes[$activity->type] ?? 'Unknown Type' }}</td>
                                    <td>{{ $activity->venue }}</td>
                                    <td>{{ $activity->address }}</td>
                                    <td>{{ $activity->target_player == 0 ? 'All Students' : 'Official Players' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($activity->start_date)->format('F d, Y h:i A') }} -
                                        {{ \Carbon\Carbon::parse($activity->end_date)->format('F d, Y h:i A') }}
                                    </td>
                                    <td>
                                        @if ($activity->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($activity->status == 0)
                                            <span class="badge text-black" style="background: yellow">Pending</span>
                                        @elseif ($activity->status == 2)
                                            <span class="badge bg-danger">Declined</span>
                                        @endif
                                    </td>
                        
                                    <td>
                                        @if((auth()->user()->roles[0]->id ==  2) && $activity->type != 3)
                                            <button type="button" class="btn btn-primary" 
                                                    {{ $activity->status == 1 ? '' : '' }}>
                                                    Edit
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editCompetitionModal"
                                                    onclick="loadActivityData({{ $activity->id }})">
                                                Edit
                                            </button>
                                        @endif
                        
                                        <button type="button" class="btn btn-info viewBtn" data-bs-toggle="modal"
                                                data-bs-target="#viewActivityModal" data-id="{{ $activity->id }}">
                                            View
                                        </button>
                                        @if((auth()->user()->roles[0]->id ==  2) && $activity->type != 3)
                                             <button type="button" class="btn btn-danger deleteBtn"
                                                {{ $activity->status == 1 ? '' : '' }}>
                                                Delete
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $activity->id }}"
                                                {{ $activity->status == 1 ? '' : '' }}>
                                                Delete
                                            </button>
                                        @endif

                                        @if (auth()->user()->hasRole('admin_sport'))
                                            <form action="{{ route('approve', $activity->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('decline', $activity->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger">
                                                    Decline
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="newCompetitionModal" tabindex="-1" aria-labelledby="newCompetitionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCompetitionModalLabel">Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form inside modal -->
                    <form action="{{ route('activity.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <!-- Title -->
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Title" name="title" required>
                            </div>
                            <!-- Target Players -->
                            <div class="col-md-6">
                                <label for="target_players" class="form-label">Target players</label>
                                <select class="form-select" name="target_player" required>
                                    <option value="" disabled selected>Select target</option>
                                    <option value="0">All Student</option>
                                    <option value="1">Official Player</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" name="content" placeholder="Content" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <label for="activity_type" class="form-label">Activity Type</label>
                                <select class="form-select" name="type" required>
                                    @if (auth()->user()->hasRole('adviser'))
                                        <option value="0">Audition</option>
                                        <option value="2">Practice</option>
                                    @endif
                                    @if (auth()->user()->hasRole('coach'))
                                        <option value="1">Tryout</option>
                                        <option value="2">Practice</option>
                                    @endif
                                    @if (auth()->user()->hasRole(['admin_sport', 'admin_org']))
                                        <option value="3" selected>Competition</option>
                                    @endif
                                </select>
                            </div>
                            @coach
    <div class="form-group col">
        <label for="organization">Sport</label>
        <input type="text" value="{{ $user->sport ? $user->sport->name : 'No sport assigned' }}" class="form-control"
            placeholder="Organization" readonly>
    </div>
@endcoach

                            @adviser
                                <div class="form-group col">
                                    <label for="organization">Organization</label>
                                    <input type="text" value="{{ $user->organization->name }}" class="form-control"
                                        placeholder="Organization" readonly>
                                </div>
                            @endadviser
                        </div>
                        <div class="row mb-3 mt-3">
                            <!-- Activity Start Date -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Activity Start Date</label>
                                <input type="datetime-local" class="form-control" name="start_date" id="start_date"
                                    required>
                            </div>

                            <!-- Activity End Date -->
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Activity End Date</label>
                                <input type="datetime-local" class="form-control" name="end_date" id="end_date"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Venue -->
                            <div class="col-md-12">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" class="form-control" placeholder="Venue" name="venue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <!-- Address -->
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Address" required>
                        </div>

                        <div class="mb-3">
                            <!-- Attachment -->
                            <label for="attachment" class="form-label">Attachment (Image)</label>
                            <input class="form-control" type="file" name="attachment" accept="image/*">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewActivityModal" tabindex="-1" aria-labelledby="viewActivityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewActivityModalLabel">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column: Activity Details -->
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                {{-- <li><strong>Sport Name:</strong> <span id="view_sport_name"></span></li> --}}
                                <li class="pt-2"><strong>Title:</strong> <span id="view_title"></span></li>
                                <li class="pt-2"><strong>Target Players:</strong> <span id="view_target_players"></span>
                                </li>
                                <li class="pt-2"><strong>Content:</strong> <span id="view_content"></span></li>
                                <li class="pt-2"><strong>Activity Type:</strong> <span id="view_type"></span></li>
                                <li class="pt-2"><strong>Activity Duration:</strong> <br> <span
                                        id="view_start_date"></span> -
                                    <span id="view_end_date"></span>
                                </li>
                                <li class="pt-2"><strong>Venue:</strong> <span id="view_venue"></span></li>
                                <li class="pt-2"><strong>Address:</strong> <span id="view_address"></span></li>
                            </ul>
                        </div>

                        <!-- Right Column: Image -->
                        <div class="col-md-6">
                            <img id="view_activity_image" src="" class="img-fluid img-thumbnail"
                                alt="Activity Image">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Close Button -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Delete Modal -->
     <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Are you sure you want to delete this?
                        <input type="hidden" name="status" value="2">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCompetitionModal" tabindex="-1" aria-labelledby="editCompetitionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCompetitionModalLabel">Edit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form inside modal -->
                    <form action="" id="editActivityForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="row mb-3">
                            <!-- Title -->
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Title" id="title"
                                    name="title" required>
                            </div>
                            <!-- Target Players -->
                            <div class="col-md-6">
                                <label for="target_players" class="form-label">Target players</label>
                                <select class="form-select" id="target_players" name="target_player" required>
                                    <option value="" disabled selected>Select target</option>
                                    <option value="0">All Student</option>
                                    <option value="1">Official Player</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" placeholder="Content" rows="3" required></textarea>
                        </div>

                 <div class="row">
                            <div class="form-group col">
                                <label for="activity_type" class="form-label">Activity Type</label>
                                <select class="form-select" name="type" required>
                                    @if (auth()->user()->hasRole('adviser'))
                                        <option value="0">Audition</option>
                                        <option value="2">Practice</option>
                                    @endif
                                    @if (auth()->user()->hasRole('coach'))
                                        <option value="1">Tryout</option>
                                        <option value="2">Practice</option>
                                    @endif
                                    @if (auth()->user()->hasRole(['admin_sport', 'admin_org']))
                                        <option value="3" selected>Competition</option>
                                    @endif
                                </select>
                            </div>

                        
                        <div class="row mb-3 mt-3">
                            <!-- Activity Start Date -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Activity Start Date</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                    required>
                            </div>

                            <!-- Activity End Date -->
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Activity End Date</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Venue -->
                            <div class="col-md-12">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" class="form-control" id="venue" placeholder="Venue"
                                    name="venue" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <!-- Address -->
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Address" required>
                        </div>

                        <div class="mb-3">
                            <!-- Attachment -->
                            <label for="attachment" class="form-label">Attachment (Image)</label>
                            <input class="form-control" type="file" id="attachment" name="attachment[]"
                                accept="image/*" multiple>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>


   

@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable();

        function loadActivityData(activityId) {
            // Call the show route using AJAX
            $.ajax({
                url: '/activity/' + activityId, // Assuming your route is like /activity/{id}
                method: 'GET',
                success: function(data) {
                    // Populate the modal fields with data
                    $('#editActivityForm #title').val(data.title);
                    $('#editActivityForm #target_players').val(data.target_player);
                    $('#editActivityForm #content').val(data.content);
                    $('#editActivityForm #type').val(data.type);
                    $('#editActivityForm #start_date').val(data.start_date);
                    $('#editActivityForm #end_date').val(data.end_date);
                    $('#editActivityForm #venue').val(data.venue);
                    $('#editActivityForm #address').val(data.address);

                    // Set the form action dynamically for updating the activity
                    $('#editActivityForm').attr('action', '/activity/' + activityId);
                },
                error: function(xhr) {
                    console.error('Error fetching activity data', xhr);
                    alert('Failed to load activity data.');
                }
            });
        }

        function getCurrentDateTime() {
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
        }

        $(() => {

            const currentDateTime = getCurrentDateTime();

            $('#start_date').attr('min', currentDateTime);
            $('#end_date').attr('min', currentDateTime);

            $('#start_date').on('change', function() {
                var startDate = $(this).val();
                $('#end_date').attr('min', startDate);
            });

            $('.viewBtn').click(function() {
                const activityId = $(this).data('id');

                // Fetch the activity details via an API or AJAX call
                fetch('/activity/' + activityId)
                    .then(response => response.json())
                    .then(activity => {
                        // Convert activity type number to text
                        let activityTypeText;
                        switch (activity.type) {
                            case '0':
                            case 0:
                                activityTypeText = 'Audition';
                                break;
                            case '1':
                            case 1:
                                activityTypeText = 'Tryout';
                                break;
                            case '2':
                            case 2:
                                activityTypeText = 'Practice';
                                break;
                            case '3':
                            case 3:
                                activityTypeText = 'Competition';
                                break;
                            default:
                                activityTypeText = activity.type || 'N/A';
                        }

                        // Populate the fields in the modal
                        // $('#view_sport_name').text(activity.sport_name || 'N/A');
                        $('#view_title').text(activity.title || 'N/A');
                        if (activity.target_player === 0) {
                            $('#view_target_players').text('All Student');
                        } else if (activity.target_player === 1) {
                            $('#view_target_players').text('Official Player');
                        }
                        $('#view_content').text(activity.content || 'N/A');
                        $('#view_type').text(activityTypeText); // Using the converted type text
                        $('#view_start_date').text(activity.start_date || 'N/A');
                        $('#view_end_date').text(activity.end_date || 'N/A');
                        $('#view_venue').text(activity.venue || 'N/A');
                        $('#view_address').text(activity.address || 'N/A');

                        // Handle the image
                        const imageUrl = activity.attachment ? '/storage/' + activity.attachment :
                            '/path-to-default-image.jpg';
                        $('#view_activity_image').attr('src', imageUrl);
                    })
                    .catch(error => {
                        console.error('Error fetching activity details:', error);
                    });

                // Show the modal
                $('#viewActivityModal').modal('show');
            });

            $('.deleteBtn').click(function() {
                $('#deleteForm').attr('action', 'activity/' + $(this).data('id'));
            })

        })
    </script>
@endpush
