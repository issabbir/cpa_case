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
                    <h4 class="card-title">Bill Submit</h4><!---->
                    <hr>
                    @if(Session::has('message'))
                        <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
{{--                    <form method="POST" id="search-form" name="search-form" action="">--}}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Lawyer Name</label>
                                            <select required class="custom-select select2" name="lawyer"
                                                    id="lawyer">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($lawyerList as $data)
                                                    <option value="{{$data->lawyer_id}}">{{$data->lawyer_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Case Number</label>
                                            <select class="custom-select select2" name="case_no"
                                                    id="case_no">
                                                <option value="">-- Please select an option --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="required">Service Date From</label>
                                            <div class="input-group date" id="serviceDateFromDP"
                                                 data-target-input="nearest">
                                                <input required type="text"
                                                       value=""
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#serviceDateFromDP"
                                                       id="service_date_from"
                                                       name="service_date_from"
                                                       placeholder="Service Date From"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="required">Service Date To</label>
                                            <div class="input-group date" id="serviceDateToDP"
                                                 data-target-input="nearest">
                                                <input required type="text"
                                                       value=""
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#serviceDateToDP"
                                                       id="service_date_to"
                                                       name="service_date_to"
                                                       placeholder="Service Date To"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
{{--                                        <div class="d-flex justify-content-end col">--}}
                                            <button type="button" onclick="billVoucher()" class="btn btn btn-dark shadow mb-2 btn-secondary">
                                                Bill Voucher
                                            </button>

                                            <button type="button" onclick="chkTable()" class="btn btn btn-dark shadow mb-2 btn-secondary">
                                                Search
                                            </button>
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                    </form>--}}
                </div>
            </div>

            <div id="bill_sub"></div>
{{--            <div class="card">--}}
{{--                <div class="card-body"><h4 class="card-title">Bill Submission List</h4>--}}
{{--                    <div class="table-responsive">--}}
{{--                        <table class="table table-sm datatable mdl-data-table dataTable" id="final-results">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>#</th>--}}
{{--                                <th>SL.</th>--}}
{{--                                <th>DATE</th>--}}
{{--                                <th>CASE NO.</th>--}}
{{--                                <th>SERVICE NAME</th>--}}
{{--                                <th>BILL AMOUNT</th>--}}
{{--                                <th>APPROVE AMOUNT</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody></tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript">

        $('select[name="lawyer"]').on('change', function() {
            var lawyer_id = $(this).val();

            if(lawyer_id) {
                $.ajax({
                    url: '/lawyer-bill/'+lawyer_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        // alert(data);
                        $('select[name="case_no"]').empty();
                        $('select[name="case_no"]').append('<option value="">'+ '-- Please select an option --' +'</option>');
                        $.each(data, function(key, value) {
                            $('select[name="case_no"]').append('<option value="'+ value.case_id +'">'+ value.case_no +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="case_no"]').empty();
            }
        });

        function chkTable() {
            if ($('#lawyer').val() == 0) {
                Swal.fire({
                    title: 'Lawyer Name is Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#service_date_from').val() == 0) {
                Swal.fire({
                    title: 'Service Date From is Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#service_date_to').val() == 0) {
                Swal.fire({
                    title: 'Service Date To is Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                search();
            }
        }

        function search()
        {
            var lawyer = $("#lawyer").val();
            var case_no = $("#case_no").val();
            var service_date_from = $("#service_date_from").val();
            var service_date_to = $("#service_date_to").val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                type:"POST",
                url: APP_URL + "/bill-info-datatable",
                data: { 'lawyer' : lawyer, 'case_no' : case_no, 'service_date_from' : service_date_from, 'service_date_to' : service_date_to },
                async: true,
                success: function(msg){
                    // alert(msg);
                    $('#bill_sub').html(msg);
                }
            });
        }

        function billVoucher(){
            var lawyer = $("#lawyer").val();
            var case_no = $("#case_no").val();
            var service_date_from = $("#service_date_from").val();
            var service_date_to = $("#service_date_to").val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                type:"POST",
                url: APP_URL + "/bill-voucher-datatable",
                data: { 'lawyer' : lawyer, 'case_no' : case_no, 'service_date_from' : service_date_from, 'service_date_to' : service_date_to },
                async: true,
                success: function(msg){
                    // alert(msg);
                    $('#bill_sub').html(msg);
                }
            });
        }

        $(document).ready(function () {
            $('#serviceDateFromDP').datetimepicker({
                format: 'DD-MM-YYYY',
                // format: 'L',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
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

            $('#serviceDateToDP').datetimepicker({
                format: 'DD-MM-YYYY',
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
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

            $('#search-form').on('submit', function (e) {
                // e.preventDefault();
                // oTable.draw();
                // search();
            });

        });

        var oTable = $('#final-results').DataTable({
            processing: true,
            serverSide: true,
            // bDestroy: true,
            // pageLength: 5,
            // bFilter: true,
            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            // columnDefs: [ {
            //     orderable: false,
            //     className: 'select-checkbox',
            //     targets:   0
            // } ],
            // select: {
            //     style:    'os',
            //     selector: 'td:first-child'
            // },
            // order: [[ 1, 'asc' ]],
            ajax: {
                url: APP_URL + "/bill-info-datatable",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.lawyer = $('#lawyer').val();
                    d.case_no = $('#case_no').val();
                    d.service_date_from = $('#service_date_from').val();
                    d.service_date_to = $('#service_date_to').val();
                }
            },
            "columns": [
                {"data": "checkbox"},
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "service_date"},
                {"data": "case_no"},
                {"data": "service_name"},
                {"data": "bill_amount"},
                {"data": "bill_id"},
                // {"data": "bill_status"},
            ]
        });
    </script>
@endsection
