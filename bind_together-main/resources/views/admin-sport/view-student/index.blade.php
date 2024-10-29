@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>View Student</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Student Number</th>
                            <th>Student Name</th>
                            <th>Year Level</th>
                            <th>Campus</th>
                            <th>Organization Name</th>
                            <th>Sport Name</th>
                            <th>Coach Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->user->student_number }}</td>
                                <td>{{ $student->user->firstname }} {{ $student->user->lastname }}</td>
                                <td>{{ $student->user->year_level }}</td>
                                <td>{{ $student->user->campus->name }}</td>
                                <td>{{ $student->user->organization->name ?? '-' }}</td>
                                <td>{{ $student->user->sport->name ?? '-' }}</td>
                                <td>{{ $superior->firstname }} {{ $superior->lastname }}</td>
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
        $(() => {
            $('#datatable').DataTable();
        })
    </script>
@endpush