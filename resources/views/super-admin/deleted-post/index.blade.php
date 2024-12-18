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
            <h4>Deleted Post</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Owner</th>
                            <th>Reason for deletion</th>
                            <th>Date Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportedNewsfeeds as $reportedNewsfeed)
                        <tr>
                            <td>{{ $reportedNewsfeed->description }}</td>
                            <td>{{ $reportedNewsfeed->user->firstname }} {{ $reportedNewsfeed->user->lastname }}</td>
                            <td>
                                @php
                                $string = "";

                                foreach($reportedNewsfeed->reportedPosts as $reported_post){
                                $string .= $reported_post->other_reason ? $reported_post->other_reason : $reported_post->reason . " ";
                                }

                                $words = explode(' ', strtolower($string));
                                $uniqueWords = array_unique($words);
                                $result = implode(' ', $uniqueWords);

                                @endphp
                                {{ucfirst($result);}}
                            </td>
                            <td>{{ $reportedNewsfeed->created_at }}</td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#restoreModal"
                                    onclick="setStatus(1, {{ $reportedNewsfeed->id }})">Restore</button>
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
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Restore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to restore?</p>
                    <input type="hidden" name="status" id="statusInput">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Restore</button>
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

    function setStatus(status, newsfeed_id) {
        document.getElementById('statusInput').value = status;
        document.getElementById('statusForm').action = `/reported-post-update/${newsfeed_id}`;
    }
</script>
@endpush