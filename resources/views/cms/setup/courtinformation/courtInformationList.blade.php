<div class="card">
    <div class="card-header">
        <h4 class="card-title">Court Information List</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="court_info">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>COURT NAME</th>
                        <th>COURT ADDRESS</th>
                        <th>COURT DESCRIPTION</th>
                        <th>COURT CATEGORY</th>
                        <th>DIVISION</th>
                        <th>DISTRICT</th>
                        <th>THANA</th>
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

@section('footer-script')
    <!--Load custom script-->
    <script>
        $("#reset").click(function () {
            $("#court_info_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            courtinfolist();

            $('#division').change(function () {
                var division = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '/get-district-ajax',
                    data: {division: division},
                    success: function (msg) {
                        $("#district").html(msg);
                    }
                });
            });

            $('#district').change(function () {
                var district = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '/get-thana-ajax',
                    data: {district: district},
                    success: function (msg) {
                        $("#thana").html(msg);
                    }
                });
            });

        });

        function courtinfolist() {
            $('#court_info').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/court-info-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'court_name', "name": 'court_name'},
                    {"data": 'court_address', "name": 'court_address'},
                    {"data": 'description', "name": 'description'},
                    {"data": 'court_category_name', "name": 'court_category_name'},
                    {"data": 'division_name', "name": 'division_name'},
                    {"data": 'district_name', "name": 'district_name'},
                    {"data": 'thana_name', "name": 'thana_name'},
                    {"data": 'status', "name": 'status'},
                    {"data": 'action', "name": 'action'},
                ]

            });
        }
    </script>
@endsection
