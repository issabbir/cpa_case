<div class="card">
    <div class="card-header">
        <h4 class="card-title">Rate Chart List</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="rate_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>SERVICE</th>
                        <th>RATE FROM</th>
                        <th>RATE TO</th>
                        <th>ACTIVE FROM</th>
                        <th>ACTIVE TO</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--@foreach($rateChart as $key=>$lot)
                        <tr>
                            <td>{{++$key}}.</td>
                            <td>{{$lot->assignment_type_name}}</td>
                            <td>{{$lot->minimum_rate}}</td>
                            <td>{{$lot->maximum_rate}}</td>
                            <td>{{ date('d-m-Y', strtotime($lot->active_from)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($lot->active_to)) }}</td>
                            <td>{{$lot->active_yn == "Y" ? 'ACTIVE' : 'INACTIVE'}}</td>
                            <td style="display:none;">{{$lot->rate_chart_id}}</td>
                            <td>

                                <a href="{{ url('/rate-chart/edit') }}/{{$lot->rate_chart_id}}"
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

@section('footer-script')
    <script>
        $("#reset").click(function () {
            $("#rate_chart_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            rateChartlist();
            $('#activeFromDP').datetimepicker({
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

            $('#activeToDP').datetimepicker({
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
        });

        function rateChartlist()
        {
            $('#rate_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/rate-chart-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'assignment_type_name', "name": 'assignment_type_name'},
                    {"data": 'minimum_rate', "name": 'minimum_rate'},
                    {"data": 'maximum_rate', "name": 'maximum_rate'},
                    {"data": 'active_from', "name": 'active_from'},
                    {"data": 'active_to', "name": 'active_to'},
                    {"data": 'status', "name": 'status'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }
    </script>
@endsection
