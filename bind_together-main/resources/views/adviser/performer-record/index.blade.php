@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                @if ($status == 0)
                    <h4>Audition List</h4>
                @else
                    <h4>Official Player</h4>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Year Level</th>
                                <th>Campus</th>
                                <th>Email</th>
                                <th>Height</th>
                                <th>Weight</th>
                                {{-- <th>Person in Contact</th> --}}
                                <th>Emergency Contact</th>
                                <th>Relationship</th>
                                <th>COR</th>
                                <th>Photocopy</th>
                                @if ($status == 0)
                                    <th>Other File</th>
                                @else
                                    <th>Parent Consent</th>
                                @endif
                                <th>Date Registered</th>
                                <th>Status</th>
                                @if ($status == 0)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($auditions as $audition)
                                <tr>
                                    <td>{{ $audition->user->firstname }} {{ $audition->user->lastname }}</td>
                                    <td>{{ $audition->user->year_level }} Year</td>
                                    <td>{{ $audition->user->campus->name }} Year</td>
                                    <td>{{ $audition->user->email }} Year</td>
                                    <td>{{ $audition->height }}</td>
                                    <td>{{ $audition->weight }}</td>
                                    <td>{{ $audition->emergency_contact }}</td>
                                    <td>{{ $audition->relationship }}</td>
                                    <td><img src="{{ asset('storage/' . $audition->certificate_of_registration) }}"
                                            alt=""></td>
                                    <td><img src="{{ asset('storage/' . $audition->photo_copy_id) }}" alt=""></td>
                                    @if ($status == 0)
                                        <td><img src="{{ asset('storage/' . $audition->other_file) }}" alt="">
                                        </td>
                                    @else
                                        <td><img src="{{ asset('storage/' . $audition->parent_consent) }}" alt="">
                                        </td>
                                    @endif
                                    {{-- <td>{{ $audition->type ?? '' }}</td> --}}
                                    <td>{{ $audition->status == 0 ? 'Pending' : 'Approved' }}</td>
                                    <td>{{ $audition->created_at }}</td>
                                    @if ($status == 0)
                                        <td>
                                            <button class="btn btn-primary approveBtn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#approveModal"
                                                data-id="{{ $audition->id }}">Approve</button>
                                            <button class="btn btn-secondary declineBtn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#declineModal"
                                                data-id="{{ $audition->id }}">Decline</button>
                                            <button type="button" class="btn btn-info viewBtn" data-bs-toggle="modal"
                                                data-bs-target="#viewAuditionModal" data-id="{{ $audition->id }}">
                                                View
                                            </button>

                                            {{-- <button class="btn btn-danger">Delete</button> --}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable();
    </script>
@endpush
