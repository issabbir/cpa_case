<div class="card">
    <div class="card-header">
        <h4 class="card-title">Organization List</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="organization_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ORGANIZATION NAME</th>
                        <th>ADDRESS</th>
                        <th>EMAIL</th>
                        <th>FAX</th>
                        <th>CONTACT NO</th>
                        <th>No. of Employee</th>
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
<div id="cover-spin"></div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                <button type="button" class="close" onclick="javascript:window.location.reload()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        <input readonly type="text" id="modal_org_name" name="modal_org_name"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                    <input type="hidden" id="modal_org_id" name="modal_org_id">
                                </div>
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Organization Name (Bangla): </label>--}}
{{--                                        <input type="text" id="modal_org_name_ban" name="modal_org_name_ban"--}}
{{--                                               class="form-control"--}}
{{--                                               autocomplete="off">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Employee Name: </label>
                                        <input required type="text" id="modal_emp_name" name="modal_emp_name"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Employee Name (Bangla): </label>
                                        <input type="text" id="modal_emp_name_ban" name="modal_emp_name_ban"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Designation: </label>
                                        <input required type="text" id="modal_desig" name="modal_desig"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Address: </label>
                                        <input required type="text" id="modal_address" name="modal_address"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Contact No: </label>
                                        <input required type="text" id="modal_contact" name="modal_contact"
                                               class="form-control"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" name="active_status" id="active_status" value="Y">
                                <input type="hidden" name="outsider_status" id="outsider_status" value="Y">
                                <input type="hidden" name="org_emp_id" id="org_emp_id">
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="required">Active Status: </label>--}}
{{--                                        <select class="custom-select form-control"--}}
{{--                                                id="active_status" name="active_status"--}}
{{--                                                required>--}}
{{--                                            <option value="">Select One</option>--}}
{{--                                            <option value="{{ \App\Enums\YesNoFlag::YES }}">--}}
{{--                                                Active--}}
{{--                                            </option>--}}
{{--                                            <option value="{{ \App\Enums\YesNoFlag::NO }}">--}}
{{--                                                In-Active--}}
{{--                                            </option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="required">Outsider: </label>--}}
{{--                                        <select class="custom-select form-control"--}}
{{--                                                id="outsider_status" name="outsider_status"--}}
{{--                                                required>--}}
{{--                                            <option value="">Select One</option>--}}
{{--                                            <option value="{{ \App\Enums\YesNoFlag::YES }}">--}}
{{--                                                Outsider--}}
{{--                                            </option>--}}
{{--                                            <option value="{{ \App\Enums\YesNoFlag::NO }}">--}}
{{--                                                CPA--}}
{{--                                            </option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="javascript:window.location.reload()" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="modal_submit" onclick="return chkTable()">Save</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Employee List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable mdl-data-table dataTable" id="emp_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>DESIGNATION</th>
                                    <th>ADDRESS</th>
                                    <th>CONTACT NO</th>
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
        </div>
    </div>
</div>
@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $("#reset").click(function () {
            $("#rate_chart_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });
    </script>

    <script type="text/javascript">
        // $(".add-org-comp").click(function () {
        //     var myModal = $('#exampleModal');
        //     myModal.modal({show: true});
        //     return false;
        // });

        function modalFunc(id) {
            getOrgName(id);
            var myModal = $('#exampleModal');
            myModal.modal({show: true});
            employeelist(id);
            return false;
        }

        function modalEmp(id) {
            var myModal = $('#exampleModal');
            $.ajax({
                type: 'get',
                url: '/emp-reg/edit',
                data: {id: id},
                success: function (value) {
                    // console.log(value);
                    // $.each(msg, function(key, value) {
                        $('#modal_emp_name', myModal).val(value.employee_name);
                        $('#modal_emp_name_ban', myModal).val(value.employee_name_bng);
                        $('#modal_desig', myModal).val(value.employee_designation);
                        $('#modal_address', myModal).val(value.address);
                        $('#modal_contact', myModal).val(value.contact_no);
                        $('#org_emp_id', myModal).val(value.org_party_emp_id);
                    // });

                }
            });
        }
        function employeelist(id)
        {
            var myModal = $('#exampleModal');
            $('#emp_table', myModal).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/employee-dataTable',
                    'type': 'POST',
                    data: {id: id},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    // {"data": 'organization_name', "name": 'organization_name'},
                    {"data": 'employee_name', "name": 'organization_name'},
                    {"data": 'employee_designation', "name": 'employee_designation'},
                    {"data": 'address', "name": 'address'},
                    {"data": 'contact_no', "name": 'contact_no'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }

        function getOrgName(id){
            var myModal = $('#exampleModal');
            $.ajax({
                type: 'get',
                url: '/get-org-name-ajax',
                data: {id: id},
                success: function (msg) {
                    // alert(msg);
                    $.each(msg, function(key, value) {
                        $('#modal_org_name', myModal).val(value.organization_name);
                        $('#modal_org_id', myModal).val(value.organization_id);
                    });

                }
            });
        }

        function chkTable() {
            if ($('#modal_emp_name').val == '') {
                Swal.fire({
                    title: 'Employee Name Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#modal_desig').val == '') {
                Swal.fire({
                    title: 'Employee Designation Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#modal_address').val == '') {
                Swal.fire({
                    title: 'Address is Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#modal_contact').val == '') {
                Swal.fire({
                    title: 'Contact No. is Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                return true;
            }
        }

        $('#modal_submit').click(function () {
            let modal_org_name = $("#modal_org_name").val();
            let modal_org_id = $("#modal_org_id").val();
            let modal_emp_name = $("#modal_emp_name").val();
            let modal_emp_name_ban = $("#modal_emp_name_ban").val();
            let modal_desig = $("#modal_desig").val();
            let modal_address = $("#modal_address").val();
            let modal_contact = $("#modal_contact").val();
            let active_status = ($('#active_status').val());
            let outsider_status = ($('#outsider_status').val());
            let org_emp_id = ($('#org_emp_id').val());


            $('#cover-spin').show();

            $.ajax({
                type: 'get',
                url: '/add-employee',
                data: {
                    modal_org_name: modal_org_name,
                    modal_org_id: modal_org_id,
                    modal_emp_name: modal_emp_name,
                    modal_emp_name_ban: modal_emp_name_ban,
                    modal_desig: modal_desig,
                    modal_address: modal_address,
                    modal_contact: modal_contact,
                    active_status: active_status,
                    outsider_status: outsider_status,
                    org_emp_id: org_emp_id
                },
                success: function (msg) {
                    //console.log(msg)
                    if (msg.code == '1') {
                        $("#exampleModal").modal('hide');
                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            // $("#modal_org_name").val('');
                            // $("#modal_org_name_ban").val('');
                            $("#modal_emp_name").val('');
                            $("#modal_emp_name_ban").val('');
                            $("#modal_desig").val('');
                            $("#modal_address").val('');
                            $("#modal_contact").val('');
                            $("#org_emp_id").val('');
                            // $('#active_status').val('');
                            // $('#outsider_status').val('');
                            location.reload();
                        });
                    } else if (msg.code == '90') {//alert('here')
                        $("#exampleModal").modal('hide');
                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            // $("#modal_org_name").val('');
                            // $("#modal_org_name_ban").val('');
                            $("#modal_emp_name").val('');
                            $("#modal_emp_name_ban").val('');
                            $("#modal_desig").val('');
                            $("#modal_address").val('');
                            $("#modal_contact").val('');
                            $("#org_emp_id").val('');
                            // $('#active_status').val('');
                            // $('#outsider_status').val('');
                            // location.reload();
                        });
                    } else {

                        $('#cover-spin').hide();
                        Swal.fire({
                            title: msg.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            // $("#modal_org_name").val('');
                            // $("#modal_org_name_ban").val('');
                            $("#modal_emp_name").val('');
                            $("#modal_emp_name_ban").val('');
                            $("#modal_desig").val('');
                            $("#modal_address").val('');
                            $("#modal_contact").val('');
                            $("#org_emp_id").val('');
                            // $('#active_status').val('');
                            // $('#outsider_status').val('');
                            //$("#modal_amount").val('');
                            //$("#modal_comment").val('');
                            // location.reload();
                        });
                    }

                }
            });
        });

        $(document).ready(function () {
            organizationlist();
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

        function organizationlist()
        {
            $('#organization_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/organization-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'organization_name', "name": 'organization_name'},
                    // {"data": 'organization_name_bng', "name": 'organization_name_bng'},
                    {"data": 'address', "name": 'address'},
                    {"data": 'email', "name": 'email'},
                    {"data": 'faxno', "name": 'faxno'},
                    {"data": 'contact_no', "name": 'contact_no'},
                    // {"data": 'organization_type', "name": 'organization_type'},
                    {"data": 'number_of_emp', "name": 'number_of_emp'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }
    </script>
@endsection
