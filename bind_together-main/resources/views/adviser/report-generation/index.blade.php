@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Report Generation</h4>
            </div>
            <div class="card-body" style="min-height: 70vh">
                <div class="row">
                    <div class="form-group col-4">
                        <label for="">Type of Report</label>
                        <select name="type" id="" class="form-select">
                            <option value="" selected disabled>Select Type of Report</option>
                            <option value="1">List of Registered Participants</option>
                            <option value="2">List of Official Players</option>
                            <option value="3">Adviser</option>
                            <option value="4">Activities</option>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <label for="">Start Date</label>
                        <input type="date" name="start_date" class="form-control">

                        <div class="form-group mt-3">
                            <label for="">Year Level</label>
                            <select name="year_level" id="" class="form-select">
                                <option value="" selected disabled>Select Year Level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-2">
                        <label for="">End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="form-group col-4">
                        <label for="">Organization Name</label>
                        <select name="organization_id" id="" class="form-select">
                            <option value="" selected disabled>Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-5 d-flex">
                    <button class="btn btn-primary">Print</button>
                    <button class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
@endsection
