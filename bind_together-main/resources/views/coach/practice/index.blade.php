@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Practice</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Response</th>
                                <th>Reason</th>
                                <th>Date Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($participants as $participant)
                                <tr>
                                    <td>{{ $participant->user->firstname }} {{ $participant->user->lastname }}</td>
                                    @if ($participant->status == 1)
                                        <td>Going</td>
                                        @else
                                        <td>Not Going</td>
                                    @endif
                                    <td>{{ $participant->reason ?? '-' }}</td>
                                    <td>{{ $participant->created_at }}</td>
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
        $('#datatable').DataTable()
    </script>
@endpush
