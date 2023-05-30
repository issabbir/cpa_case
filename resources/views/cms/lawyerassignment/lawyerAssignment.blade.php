@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Table Start -->
                <div class="card-body">
                    <h4 class="card-title">Lawyer Assignment</h4>
                    <hr>
                    <form method="POST" id="search-form" name="search-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="custom-select form-control select2" id="status"
                                            name="status">
                                        <option value="">Select One</option>
                                        @if($caseStatusList)
                                            @foreach($caseStatusList as $data)
                                                <option
                                                    value="{{$data->case_status_id}}">{{$data->case_status_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Court</label>
                                    <select class="custom-select form-control select2" id="court"
                                            name="court">
                                        <option value="">Select One</option>
                                        @if($courtList)
                                            @foreach($courtList as $data)
                                                <option
                                                    value="{{$data->court_id}}">{{$data->show_value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Case No.</label>
                                    <select class="custom-select form-control select2" id="case_no"
                                            name="case_no">
                                        <option value="">Select One</option>
                                        @if($caseData)
                                            @foreach($caseData as $data)
                                                <option
                                                    value="{{$data->case_id}}">{{$data->case_no}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <div class="d-flex justify-content-start col">
                                    <button type="submit" class="btn btn btn-dark shadow mb-1 btn-secondary">
                                        Search
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body"><h4 class="card-title">Case List</h4>
                    <div class="table-responsive">
                        <table class="table table-sm datatable mdl-data-table" id="final-results">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CASE NO.</th>
                                <th>CATEGORY</th>
                                <th>COURT</th>
                                <th>COMPLAINANT</th>
                                <th>DEFENDENT</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <script type="text/javascript">

        $(document).ready(function () {
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                oTable.draw();
            });
        });

        var oTable = $('#final-results').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 5,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + "/lawyer-assignment-case-datatable",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.status = $('#status').val();
                    d.court = $('#court').val();
                    d.case_no = $('#case_no').val();
                }
            },
            "columns": [
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "case_no"},
                {"data": "category_name"},
                {"data": "court_name"},
                {"data": "complainant"},
                {"data": "defendant"},
                {"data": "case_status_name"},
                {"data": "action"}
            ]
        });

    </script>

@endsection
