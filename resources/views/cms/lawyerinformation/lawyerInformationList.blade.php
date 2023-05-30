<div class="card">
    <div class="card-header">
        <h4 class="card-title">Lawyer Information List</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="lawyer_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>MOBILE</th>
                        <th>ADDRESS</th>
                        <th>CONTRACT NO</th>
                        <th>EXPIRE DATE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('footer-script')
    <!--Load custom script-->
    <script>
        $("#reset").click(function () {
            $("#lawyer_info_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            lawyerlistData();

            $('#enlistment_dateDP').datetimepicker({
                format: 'DD-MM-YYYY',
                // format: 'L',
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });

            $('#expired_onDP').datetimepicker({
                format: 'DD-MM-YYYY',
                // format: 'L',
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });

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

            $('#bank').change(function () {
                var bank = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '/get-branch-ajax',
                    data: {bank: bank},
                    success: function (msg) {
                        $("#branch").html(msg);
                    }
                });
            });
        });

        function lawyerlistData()
        {
            $('#lawyer_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/lawyer-info-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'lawyer_name', "name": 'lawyer_name'},
                    {"data": 'contact_no', "name": 'contact_no'},
                    {"data": 'present_address', "name": 'present_address'},
                    {"data": 'enlistment_no', "name": 'enlistment_no'},
                    {"data": 'expiry_date', "name": 'expiry_date'},
                    {"data": 'status', "name": 'status'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }
    </script>
@endsection
