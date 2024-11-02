@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Report Generation admin org</h4>
        </div>
        <div class="card-body" style="min-height: 70vh">
            <form action="{{ route('reports.generateAdminOrg') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-4">
                        <label for="type">Type of Report</label>
                        <select name="type" id="type_of_report" class="form-select">
                            <option value="" selected disabled>Select Type of Report</option>
                            <option value="3">Activity</option>
                            <option value="1">List of Registered Participants</option>
                            <option value="2">List of Official Players</option>
                            <option value="4">List of Advisers</option>
                        </select>

                        <div id="activitiesDropdown" style="display: none;" class="mt-3">
                            <label for="activity_type">Activity Type</label>
                            <select name="activity_type" id="activity_type" class="form-select">
                                <option value="" selected disabled>Select Activity Type</option>
                                <option value="3">Competition</option>
                                <option value="1">Audition</option>
                            </select>
                        </div>
                        <div class="form-group col-4 mt-5" id="approvalCheckboxes" style="display: none;">
                            <label>Status</label><br>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="status[]" value="approve" id="approve">
                                <label for="approve" class="form-check-label">Approve</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="status[]" value="declined" id="declined">
                                <label for="declined" class="form-check-label">Decline</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="status[]" value="all" id="all">
                                <label for="all" class="form-check-label">All</label>
                            </div>
                        </div>

                    </div>


                    <div class="form-group col-2">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>

                    <div class="form-group col-2">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control">

                        <div class="form-group mt-3" id="yearLevelSection">
                            <label for="">Year Level</label><br>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="year_level[]" value="1" id="first_year">
                                <label for="first_year" class="form-check-label">1st Year</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="year_level[]" value="2" id="second_year">
                                <label for="second_year" class="form-check-label">2nd Year</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="year_level[]" value="3" id="third_year">
                                <label for="third_year" class="form-check-label">3rd Year</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="year_level[]" value="4" id="fourth_year">
                                <label for="fourth_year" class="form-check-label">4th Year</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="year_level[]" value="5" id="fifth_year">
                                <label for="fifth_year" class="form-check-label">5th Year</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-4" id="orgCheckbox">
                        <label for="org-header">ORGANIZATION</label><br>

                        <!-- "All" checkbox -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="all_org" id="all_org">
                            <label for="all_org" class="form-check-label">All</label>
                        </div>

                        <!-- Sports checkboxes -->
                        @foreach ($organization as $org)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input org-checkbox" name="org[]" value="{{ $org->id }}" id="org_{{ $org->id }}">
                            <label for="org_{{ $org->id }}" class="form-check-label">{{ $org->name }}</label>
                        </div>
                        @endforeach

                        <!-- "Others" checkbox -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="org[]" value="others" id="org_others">
                            <label for="org_others" class="form-check-label">Others</label>
                        </div>
                    </div>

                </div>

                <div class="mt-5 d-flex">
                    <button type="submit" class="btn btn-primary">Download</button>
                    <select name="file_type" id="file_type" class="form-select w-25">
                        <option value="" disabled selected>Select File Type</option>
                        <option value="docx">Docx</option>
                        <option value="pdf">Pdf</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div id="file_preview" class="mt-4">
                    <textarea id="preview_content" class="form-control" rows="4" placeholder="Preview" style="max-width: 600px; width: 100%; text-align: center;"></textarea>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Toggle visibility of activity type dropdown based on selected report type
        $('#type_of_report').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue == 3) {
                $('#activitiesDropdown').slideDown();
            } else {
                $('#activitiesDropdown').slideUp();
            }
            if (selectedValue == 1) {
                $('#approvalCheckboxes').slideDown();
            } else {
                $('#approvalCheckboxes').slideUp();
            }
            if (selectedValue == 3 || selectedValue == 4) {
                $('#yearLevelSection').slideUp();
            } else {
                $('#yearLevelSection').slideDown();
            }
            if (selectedValue == 3) {
                $('#sportsCheckbox').slideUp();
            } else {
                $('#sportsCheckbox').slideDown();
            }
        });


        // Check or uncheck all sports when "All" is clicked
        $('#all_sports').on('change', function() {
            $('.sport-checkbox').prop('checked', $(this).is(':checked'));
        });

        // Uncheck "All" if any individual sport is unchecked
        $('.sport-checkbox').on('change', function() {
            if (!$(this).is(':checked')) {
                $('#all_sports').prop('checked', false);
            }
        });


        /*   $('#file_type').on('change', function() {
              const fileType = $(this).val();
              let previewText = '';

              switch (fileType) {
                  case 'docx':
                      previewText = 'This is a preview for a DOCX file. The report will contain formatted text and tables, similar to a Word document.';
                      break;
                  case 'pdf':
                      previewText = 'This is a preview for a PDF file. The report will be in a fixed layout, suitable for printing.';
                      break;
                  case 'excel':
                      previewText = 'This is a preview for an Excel file. The report will include data in a spreadsheet format, suitable for analysis.';
                      break;
                  default:
                      previewText = 'Select a file type to see a preview.';
              }

              $('#preview_content').text(previewText);
              $('#file_preview').show();
          }); */
    });
</script>
@endsection