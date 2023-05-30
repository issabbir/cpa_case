<div class="card">
    <div class="card-header">
        <h4 class="card-title">Case Information Update</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="case_update_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>CASE NO</th>
                        <th>CATEGORY</th>
                        <th>COURT</th>
                        <th>LAWYER</th>
                        <th>SERVICE DATE</th>
                        <th>NEXT DATE</th>
                        <th>REASON</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--@foreach($caseInfoUpdateGridList as $key=>$lot)
                        <tr>
                            <td>{{++$key}}.</td>
                            <td>{{$lot->case_no}}</td>
                            <td>{{$lot->category_name}}</td>
                            <td>{{$lot->court_name}}</td>
                            <td>{{$lot->lawyer_name}}</td>
                            <td>{{ date('d-m-Y', strtotime($lot->next_date)) }}</td>
                            <td>{{$lot->reason}}</td>
                            <td>

                                <a href="{{ url('/case-info-update/edit') }}/{{$lot->case_update_id}}"
                                   class="table-action-btn" title=" Edit">
                                    <i class="bx bx-edit cursor-pointer"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{--@section('footer-script')
    <!--Load custom script-->
    <script type="text/javascript">
        $(document).ready(function () {
            caseUpdatelist();
        });

        function caseUpdatelist()
        {
            $('#case_update_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/case-info-update-dataTable",
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'case_no', "name": 'case_no'},
                    {"data": 'category_name', "name": 'category_name'},
                    {"data": 'court_name', "name": 'court_name'},
                    {"data": 'lawyer_name', "name": 'lawyer_name'},
                    {"data": 'next_date', "name": 'next_date'},
                    {"data": 'reason', "name": 'reason'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }
    </script>
@endsection--}}
