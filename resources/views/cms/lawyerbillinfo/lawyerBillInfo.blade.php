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
                    <h4 class="card-title">Lawyer Bill</h4>
                    <hr>
                    <form method="POST" id="search-form" name="search-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="required">Lawyer</label>
                                    <select class="custom-select form-control select2" id="lawyer" required
                                            name="lawyer">
                                        <option value="">-- Please select an option --</option>
                                        @if($lawyerList)
                                            @foreach($lawyerList as $data)
                                                <option
                                                    value="{{$data->lawyer_id}}">{{$data->lawyer_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="">Case Number</label>
                                    <select class="custom-select form-control select2" id="case_no"
                                            name="case_no">
                                        <option value="">-- Please select an option --</option>
{{--                                        @if($caseList)--}}
{{--                                            @foreach($caseList as $data)--}}
{{--                                                <option--}}
{{--                                                    value="{{$data->lawyer_id}}">{{$data->lawyer_name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}
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
                                <div class="d-flex justify-content-end col">
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
                <div class="card-body"><h4 class="card-title">Bill Submission List</h4>
                    <div class="table-responsive">
                        <table class="table table-sm datatable mdl-data-table" id="final-results">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>CASE NO.</th>
                                <th>COMPLAINANT</th>
                                <th>DEFENDENT</th>
                                <th>SERVICE NAME</th>
                                <th>SERVICE DATE</th>
                                <th>COURT NAME</th>
                                <th>RATE CHART</th>
                                <th>AMOUNT</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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

    <div id="cover-spin"></div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lawyer Bill Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Lawyer Name: </label>
                            <textarea disabled
                                      rows="1" wrap="soft"
                                      name="lawyer_name"
                                      class="form-control"
                                      id="lawyer_name"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Case No: </label>
                            <textarea disabled
                                      rows="1" wrap="soft"
                                      name="case_no"
                                      class="form-control"
                                      id="case_no"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Service Date: </label>
                            <textarea disabled
                                      rows="1" wrap="soft"
                                      name="service_date"
                                      class="form-control"
                                      id="service_date"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Description: </label>
                            <textarea disabled
                                      rows="3" wrap="soft"
                                      name="modal_desc"
                                      class="form-control"
                                      id="modal_desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="required">Amount: </label>
                            <input required type="number" id="modal_amount" name="modal_amount" style="text-align: right;" class="form-control"
                                   placeholder="Amount" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label class="required">Comment: </label>
                            <input required type="text" id="modal_comment" name="modal_comment" class="form-control"
                                   placeholder="Comment" autocomplete="off">
                            <input type="hidden" id="modal_case_id" name="modal_case_id" class="form-control">
                            <input type="hidden" id="modal_rate_chart_id" name="modal_rate_chart_id"
                                   class="form-control">
                            <input type="hidden" id="modal_lawyer_id" name="modal_lawyer_id" class="form-control">
                            <input type="hidden" id="modal_assignment_type_id" name="modal_assignment_type_id"
                                   class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal_submit">Save</button>
                </div>
            </div>
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
                e.preventDefault();
                oTable.draw();
            });

        });

        $('#final-results tbody').on('click', '.editButton', function () {
            var data_row = oTable.row( $(this).parents('tr') ).data();
            var myModal = $('#exampleModal');
            //$('.modal-header').css('background-color', '#475F7B');
            //$('.modal-header').css('color', '#fff');
            $('#lawyer_name', myModal).val(data_row.lawyer_name);//alert(data_row.amount)
            $('#case_no', myModal).val(data_row.case_no_name);
            $('#modal_desc', myModal).val(data_row.description);
            $('#service_date', myModal).val(data_row.service_date);
            $('#modal_case_id', myModal).val(data_row.case_id);
            $('#modal_rate_chart_id', myModal).val(data_row.rate_chart_id);
            $('#modal_lawyer_id', myModal).val(data_row.lawyer_id);
            $('#modal_assignment_type_id', myModal).val(data_row.assignment_type_id);
            if (data_row.amount == null) {
                var max_rate = data_row.rate_chart.split('-').pop();
                $("#modal_amount").val(max_rate);
                $("#modal_comment").val(data_row.comments);
            } else {
                $('#modal_amount', myModal).val(data_row.amount);
                $('#modal_comment', myModal).val(data_row.comments);
            }

            myModal.modal({show: true});
            return false;
        });

        $('#modal_submit').click(function () {
            let amount = $("#modal_amount").val();
            let comments = $("#modal_comment").val();
            let bill_for_date = $("#service_date").val();
            let case_id = $("#modal_case_id").val();
            let rate_chart_id = $("#modal_rate_chart_id").val();
            let lawyer_id = $("#modal_lawyer_id").val();
            let assignment_type_id = $("#modal_assignment_type_id").val();


            $('#cover-spin').show();

            $.ajax({
                type: 'get',
                url: '/store-lawyer-bill-info',
                data: {
                    amount: amount,
                    comments: comments,
                    bill_for_date: bill_for_date,
                    case_id: case_id,
                    rate_chart_id: rate_chart_id,
                    lawyer_id: lawyer_id,
                    assignment_type_id: assignment_type_id
                },
                success: function (msg) {
                    if (msg.code == '1') {
                        $("#exampleModal").modal('hide');
                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            //location.reload();
                            $('#final-results').DataTable().ajax.reload();
                        });
                    } else if (msg.code == '90') {//alert('here')
                        $("#exampleModal").modal('hide');
                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function () {
                        });
                    } else {

                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            //$("#modal_amount").val('');
                            //$("#modal_comment").val('');
                        });
                    }

                }
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
                url: APP_URL + "/lawyer-bill-info-datatable",
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
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "case_no_name"},
                {"data": "complainant_badi_name"},
                {"data": "defendant_bibadi_name"},
                {"data": "service_name"},
                {"data": "service_date"},
                {"data": "court_name"},
                {"data": "rate_chart"},
                {"data": "amount","className": "text-right"},
                {"data": "lawyer_name"},
                {"data": "description"},
                {"data": "case_id"},
                {"data": "rate_chart_id"},
                {"data": "lawyer_id"},
                {"data": "assignment_type_id"},
                {"data": "comments"},
                {"data": "action"}
            ]
        });
        oTable.columns( [9,10,11,12,13,14,15] ).visible( false );

    </script>

@endsection

