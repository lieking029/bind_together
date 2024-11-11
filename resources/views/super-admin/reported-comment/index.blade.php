@extends('layouts.app')

@section('content')
<style>
    /* To style the pagination buttons */
    .dataTables_paginate .paginate_button {
        padding: 0;
        margin: 0;
    }

    /* To style individual page items */
    .dataTables_paginate .paginate_button.page-item {
        padding: 0px;
        margin: 0px;
    }

    /* To style the 'Next' and 'Previous' buttons */
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        padding: 0;
        margin: 0;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    /* To style the active page button */
    .dataTables_paginate .paginate_button.current {
        background-color: #343a40;
        color: white !important;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Reported Comment</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Owner</th>
                            <th>Report Count</th> <!-- New Column for Report Count -->
                            <th>Reported By</th> <!-- First user who reported the comment -->
                            <th>Report Reason</th>
                            <th>Status</th>
                            <th>Date Reported</th> <!-- Date when the comment was first reported -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportedComments as $reportedComment)
                        <tr>
                            <!-- Comment Description -->
                            <td>{{ $reportedComment->description }}</td>
                            {{-- @dd($reportedComment->user) --}}
                            <!-- Owner of the Comment -->
                            <td>{{ $reportedComment->user->firstname }} {{ $reportedComment->user->lastname }}</td>

                            <!-- Report Count -->
                            <td>{{ $reportedComment->report_count }}</td>
                            <!-- Count of users who reported the comment -->

                            <!-- Reported By (First User who reported the comment) -->
                            <td>
                                @php
                                $names = $reportedComment->reportedComments->map(function($reported_comment) {
                                return $reported_comment->user->firstname . " " . $reported_comment->user->lastname;
                                })->implode(', ');
                                @endphp
                                {{ $names }}
                            </td>


                            <!-- Report Reason -->
                            <td>
                                @php
                                $string = "";

                                foreach($reportedComment->reportedComments as $reported_comment){
                                $string .= $reported_comment->other_reason ? $reported_comment->other_reason : $reported_comment->reason . " ";
                                }

                                $words = explode(' ', strtolower($string));
                                $uniqueWords = array_unique($words);
                                $result = implode(' ', $uniqueWords);

                                @endphp
                                {{ucfirst($result);}}
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @if ($reportedComment->reportedComments->first()->status == 1)
                                <span class="badge text-black" style="background: yellow">Pending</span>
                                @elseif ($reportedComment->reportedComments->first()->status == 0)
                                <span class="badge bg-danger">Declined</span>
                                @elseif ($reportedComment->reportedComments->first()->status == 2)
                                <span class="badge bg-success">Approved</span>
                                @endif
                            </td>

                            <!-- Date Reported (earliest report) -->
                            <td>{{ $reportedComment->reportedComments->first()->created_at->format('Y-m-d') }}</td>

                            <!-- Action Buttons (Approve/Decline) -->
                            @php
                            $isDisabled = $reportedComment->status != 1;
                            @endphp
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actionModal"
                                    onclick="setStatus(2, {{ $reportedComment->id }})" {{ $isDisabled ? 'disabled' : '' }}>Approve</button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal"
                                    onclick="setStatus(0, {{ $reportedComment->id }}, true)" {{ $isDisabled ? 'disabled' : '' }}>Decline</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="actionModalLabel">Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to approve?</p>
                    <input type="hidden" name="status" id="statusInput">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form inside modal -->
                <form action="" id="declineForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label for="title" class="form-label">Reason</label>
                            <input type="hidden" name="status" id="statusInputDec">
                            <input type="text" class="form-control" placeholder="Type here" name="reason" required>
                        </div>
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
    $(() => {
        $('#datatable').DataTable();
    })

    function setStatus(status, newsfeed_id, is_decline = false) {
        if (!is_decline) {
            document.getElementById('statusInput').value = status;
            document.getElementById('statusForm').action = `/reported-comment-update/${newsfeed_id}`;
        } else {
            document.getElementById('statusInputDec').value = status;
            document.getElementById('declineForm').action = `/reported-comment-update/${newsfeed_id}`;
        }

    }
</script>
@endpush