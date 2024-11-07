@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4>Archive Activity</h4>
                </div>
                {{-- <div class="col text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCompetitionModal">Add
                        New</button>
                </div> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Venue</th>
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
                                    @if ($activity->target_player == 0)
                                        <td>All Students</td>
                                    @else
                                        <td>Official Players</td>
                                    @endif
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
                                        <button type="button" class="btn btn-primary restoreBtn" data-bs-toggle="modal"
                                            data-bs-target="#restoreModal"
                                            data-id="{{ $activity->id }}">Restore</button>
                                        <button type="button" class="btn btn-info viewBtn" data-bs-toggle="modal"
                                            data-bs-target="#viewActivityModal" data-id="{{ $activity->id }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $activity->id }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- View Modal --}}
    <div class="modal fade" id="viewActivityModal" tabindex="-1" aria-labelledby="viewActivityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewActivityModalLabel">View Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Read-only Form -->
                    <div class="row mb-3">
                        <!-- Title -->
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="view_title" value="" readonly>
                        </div>
                        <!-- Target Players -->
                        <div class="col-md-6">
                            <label for="target_players" class="form-label">Target players</label>
                            <input type="text" class="form-control" id="view_target_players" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" rows="3" readonly id="view_content"></textarea>
                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label for="activity_type" class="form-label">Activity Type</label>
                            <input type="text" class="form-control" value="" id="view_type" readonly>
                        </div>
                        @if (auth()->user()->hasRole('coach'))
                            <div class="form-group col">
                                <label for="organization">Sport</label>
                                <input type="text" value="{{ auth()->user()->sport->name }}" id="view_sport_id"
                                    class="form-control" readonly>
                            </div>
                        @elseif (auth()->user()->hasRole('adviser'))
                            <div class="form-group col">
                                <label for="organization">Organization</label>
                                <input type="text" value="" id="view_organization_id" class="form-control"
                                    readonly>
                            </div>
                        @endif
                    </div>

                    <div class="row mb-3 mt-3">
                        <!-- Activity Start Date -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Activity Start Date</label>
                            <input type="text" class="form-control" value="" id="view_start_date" readonly>
                        </div>

                        <!-- Activity End Date -->
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Activity End Date</label>
                            <input type="text" class="form-control" value="" id="view_end_date" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Venue -->
                        <div class="col-md-12">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" class="form-control" value="" id="view_venue" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <!-- Address -->
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" value="" id="view_address" readonly>
                    </div>

                    {{-- <div class="mb-3">
                <!-- Attachment -->
                <label for="attachment" class="form-label">Attachment (Images)</label>
                <div class="row">
                    @if ($activity->attachments)
                        @foreach ($activity->attachments as $attachment)
                            <div class="col-md-3">
                                <img src="{{ asset('storage/' . $attachment) }}" class="img-fluid img-thumbnail"
                                    alt="Attachment Image">
                            </div>
                        @endforeach
                    @else
                        <p>No attachments available.</p>
                    @endif
                </div>
            </div> --}}
                </div>
                <div class="modal-footer">
                    <!-- Close Button -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- Back Button -->
                    {{-- <a href="{{ route('activity.index') }}" class="btn btn-primary">Back</a> --}}
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
                    @method('POST')
                    <div class="modal-body">
                        Are you sure you want to delete this?
                        {{-- <input type="hidden" name="status" value="3"> --}}
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="delete_flag" value="permanent">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Restore Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Restore Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="restoreForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        Are you sure you want to restore this?
                        <input type="hidden" name="status" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable();

        $(() => {
            $('.viewBtn').click(function() {
                fetch('/activity/' + $(this).data('id'))
                    .then(response => response.json())
                    .then(activity => {
                        $('#view_title').val(activity.title)
                        $('#view_target_players').val(activity.target_player)
                        $('#view_content').val(activity.content)
                        $('#view_type').val(activity.type)
                        $('#view_start_date').val(activity.start_date)
                        $('#view_end_date').val(activity.end_date)
                        $('#view_venue').val(activity.venue)
                        $('#view_address').val(activity.address)
                    })
            })

            $('.deleteBtn').click(function() {
                $('#deleteForm').attr('action', 'delete-activity/' + $(this).data('id'));
            })

            $('.restoreBtn').click(function() {
                $('#restoreForm').attr('action', 'delete-activity/' + $(this).data('id'));
            })

        })
    </script>
@endpush
