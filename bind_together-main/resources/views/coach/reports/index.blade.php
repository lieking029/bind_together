@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Report Generation</h4>
            </div>
            <div class="card-body" style="min-height: 70vh">
                <form action="{{ route('report.generate') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="">Type of Report</label>
                            <select name="type" id="report_type" class="form-select">
                                <option value="" selected disabled>Select Type of Report</option>
                                <option value="4">Activities</option>
                                <option value="3">Practice</option>
                                <option value="1">List of Tryout Participants</option>
                                <option value="2">List of Official Players</option>
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <label for="">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group col-2">
                            <label for="">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-2 form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select" disabled>
                                <option value="" selected disabled>Select Status</option>
                                <option value="1">Approve</option>
                                <option value="2">Decline</option>
                            </select>
                        </div>
                        <div class="col">
                            <h5>Select Year Levels</h5>
                        
                            <!-- Select All Checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select_all_years">
                                <label class="form-check-label" for="select_all_years">Select All Year Levels</label>
                            </div>
                        
                            <!-- Individual Year Level Checkboxes -->
                            <div class="form-check">
                                <input class="form-check-input year-level-checkbox" type="checkbox" value="1" id="year_level_1"
                                    name="year_level[]"
                                    {{ is_array(old('year_level', auth()->user()->year_level)) && in_array(1, old('year_level', auth()->user()->year_level)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="year_level_1">1st Year</label>
                            </div>
                        
                            <div class="form-check">
                                <input class="form-check-input year-level-checkbox" type="checkbox" value="2" id="year_level_2"
                                    name="year_level[]"
                                    {{ is_array(old('year_level', auth()->user()->year_level)) && in_array(2, old('year_level', auth()->user()->year_level)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="year_level_2">2nd Year</label>
                            </div>
                        
                            <div class="form-check">
                                <input class="form-check-input year-level-checkbox" type="checkbox" value="3" id="year_level_3"
                                    name="year_level[]"
                                    {{ is_array(old('year_level', auth()->user()->year_level)) && in_array(3, old('year_level', auth()->user()->year_level)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="year_level_3">3rd Year</label>
                            </div>
                        
                            <div class="form-check">
                                <input class="form-check-input year-level-checkbox" type="checkbox" value="4" id="year_level_4"
                                    name="year_level[]"
                                    {{ is_array(old('year_level', auth()->user()->year_level)) && in_array(4, old('year_level', auth()->user()->year_level)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="year_level_4">4th Year</label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input year-level-checkbox" type="checkbox" value="5" id="year_level_5"
                                    name="year_level[]"
                                    {{ is_array(old('year_level', auth()->user()->year_level)) && in_array(5, old('year_level', auth()->user()->year_level)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="year_level_5">5th Year</label>
                            </div>
                        </div>
                        
                        {{-- <div class="col form-group">
                            <label for="">Year Level</label>
                            <select name="year_level" id="" class="form-select">
                                <option value="" selected>All Year Level</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                                <option value="5">5th Year</option>
                            </select>
                        </div> --}}
                    </div>
                    <div class="mt-5 d-flex">
                        {{-- <button class="btn btn-primary mx-2">Print</button> --}}
                        <select name="file_type" id="" class="form-select w-25">
                            <option value="pdf">PDF</option>
                        </select>
                        <button type="submit" class="btn btn-primary mx-3">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle "Select All" checkbox
        $('#select_all_years').on('change', function() {
            $('.year-level-checkbox').prop('checked', this.checked);
        });

        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.year-level-checkbox').on('change', function() {
            if (!this.checked) {
                $('#select_all_years').prop('checked', false);
            }

            // Check if all checkboxes are checked to check the "Select All" checkbox
            if ($('.year-level-checkbox:checked').length === $('.year-level-checkbox').length) {
                $('#select_all_years').prop('checked', true);
            }
        });

        // Enable/Disable Status dropdown based on Report Type selection
        $('#report_type').on('change', function() {
            var selectedType = $(this).val();
            
            // Enable Status dropdown only if report type is "Activities (4)" or "List of Tryout Participants (1)"
            if (selectedType == 4 || selectedType == 1) {
                $('#status').prop('disabled', false);  // Enable the dropdown
            } else {
                $('#status').prop('disabled', true);   // Disable the dropdown
                $('#status').val('');                 // Reset the dropdown value
            }
        });
    });
</script>
@endpush