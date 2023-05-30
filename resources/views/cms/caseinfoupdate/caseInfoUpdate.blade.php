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
                    <h4 class="card-title">Case Information Update</h4><!---->
                    <hr>
                    <form role="form-horizontal" enctype="multipart/form-data" id="case_info_update_form"
                          action="{{($editData)?url('/case-info-update/edit/'.$editData[0]->case_update_id):url('/case-info-update')}}"
                          method="post" onsubmit="return chkTable()">

                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Case No.</label>
                                            <select required class="custom-select select2" name="case_no" id="case_no">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($caseData as $case)
                                                    <option value="{{$case->case_id}}"
                                                    @if(!empty($editData)){{ ( old("case_id", ($case)?$case->case_id:'') == $editData[0]->case_id) ? "selected" : ""  }}@endif
{{--                                                    @if(!empty($editData)) @if($case->case_id == $editData[0]->case_id) {{'selected="selected"'}} @endif @endif--}}
                                                    >{{$case->case_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Case Date</label>
                                            <textarea disabled
                                                      rows="1" wrap="soft"
                                                      name="case_date"
                                                      class="form-control"
                                                      id="case_date">{{($editData)?date('d-m-Y', strtotime($editData[0]->case_date)):''}}</textarea>
                                            <input type="hidden" name="case_id" id="case_id">
                                            <input type="hidden" name="case_status_id" id="case_status_id">
                                            {{--<input type="hidden" name="assignment_type_id" id="assignment_type_id">--}}
                                            <input type="hidden" name="case_update_id" id="case_update_id"
                                                   value="{{($editData)?$editData[0]->case_update_id:''}}">
                                            <input type='hidden' value='{{($caseDocData)?count($caseDocData):'0'}}'
                                                   id="file_count">
                                            <input type='hidden' value='0' id="attach_count">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <textarea disabled
                                                      rows="1" wrap="soft"
                                                      name="case_category"
                                                      class="form-control"
                                                      id="case_category">{{($editData)?$editData[0]->category_name:''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Court</label>
                                            <textarea disabled
                                                      rows="1" wrap="soft"
                                                      name="court"
                                                      class="form-control"
                                                      id="court">{{($editData)?$editData[0]->court_name:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea disabled placeholder="Enter Description"
                                                      rows="3" wrap="soft"
                                                      name="description"
                                                      class="form-control"
                                                      id="description">{{($editData)?$editData[0]->case_description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <fieldset class="border pl-2 pt-2 col-sm-6">
                                        <legend class="w-auto required font-medium-3">Complainant/Petitioner</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="searchResultTable"
                                                           class="table table-striped table-bordered">
                                                        <thead class="text-nowrap">
                                                        <tr>
                                                            <th>Sl.</th>
                                                            <th>Name</th>
                                                            <th>Address</th>
                                                            <th>Contact No</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                    {{--<button type="button" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-comp">Delete</button>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="border pl-2 pt-2 col-sm-6">
                                        <legend class="w-auto required" style="font-size: 18px;">Defendant/Respondent</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">

                                                    <table class="table table-striped table-bordered"
                                                           id="table-def">
                                                        <thead class="p-0">
                                                        <tr class="text-nowrap">
                                                            <th style="height: 25px;text-align: left; width: 5%">Sl.
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 35%">Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 35%">
                                                                Address
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Contact No
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Case Status</label>
                                            <select required class="custom-select select2" name="case_status"
                                                    id="case_status">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($caseStatusList as $caseStatus)
                                                    <option value="{{$caseStatus->case_status_id}}"
                                                    @if(!empty($editData)) @if($caseStatus->case_status_id == $editData[0]->case_status_id) {{'selected="selected"'}} @endif @endif
                                                    >{{$caseStatus->show_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if(!empty($editData[0]->winner_party_id))
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>WINNER</label>
                                                <select class="custom-select select2" name="win_party"
                                                        id="win_party">
                                                    <option value="">-- Please select an option --</option>
                                                    @foreach($casePartyTypes as $data)
                                                        <option value="{{$data->party_type_id}}"
                                                        @if(!empty($editData)) @if($data->party_type_id == $editData[0]->winner_party_id) {{'selected="selected"'}} @endif @endif
                                                        >{{$data->party_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4" id="win_lose" style="display: none">
                                            <div class="form-group">
                                                <label>WINNER</label>
                                                <select class="custom-select select2" name="win_party"
                                                        id="win_party">
                                                    <option value="">-- Please select an option --</option>
                                                    @foreach($casePartyTypes as $data)
                                                        <option value="{{$data->party_type_id}}">{{$data->party_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Lawyer</label>
                                            <select required class="custom-select select2" name="lawyer" id="lawyer">
                                                <option value="">-- Please select an option --</option>
                                                @if(!empty($lawyerList))
                                                    @foreach($lawyerList as $lawyer)
                                                        <option value="{{$lawyer->pass_value}}"
                                                        @if(!empty($editData)) @if($lawyer->pass_value == $editData[0]->lawyer_id) {{'selected="selected"'}} @endif @endif
                                                        >{{$lawyer->show_value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Service Name</label>
                                            <select  class="custom-select select2" name="assignment_type_id"
                                                    id="assignment_type_id">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($serviceList as $service)
                                                    <option value="{{$service->assignment_type_id}}"
                                                    @if(!empty($editData)) @if($service->assignment_type_id == $editData[0]->assignment_type_id) {{'selected="selected"'}} @endif @endif
                                                    >{{$service->assignment_type_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Honorable Judges Name</label>
                                            <input type="text" id="judges_name" name="judges_name"
                                                   class="form-control"
                                                   placeholder="Honorable Judges Name"
                                                   value="{{($editData)?$editData[0]->judges_name:''}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="">Last Update Date/Service Date</label>
                                            <div class="input-group date" id="lastUpdateDateDP"
                                                 data-target-input="nearest">
                                                <input  type="text"
                                                        value="{{old('last_update_date',isset($editData[0]->case_update_date) ? date('d-m-Y', strtotime($editData[0]->case_update_date)) : '')}}"

                                                        {{--value="{{($editData)?date('d-m-Y', strtotime($editData[0]->case_update_date)):''}}"--}}
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#lastUpdateDateDP"
                                                       id="last_update_date"
                                                       name="last_update_date"
                                                       placeholder="Last Update Date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="next_date">
                                            <label class="">Next Date</label>
                                            <div class="input-group date" id="nextDateDP" data-target-input="nearest">
                                                <input  type="text"
                                                       value="{{($editData)?date('d-m-Y', strtotime($editData[0]->next_date)):''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#nextDateDP"
                                                       id="next_date"
                                                       name="next_date"
                                                       placeholder="Next Date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Comments</label>
                                            <input  type="text" id="comments" name="comments"
                                                   class="form-control"
                                                   placeholder="Comments"
                                                   value="{{($editData)?$editData[0]->comments:''}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Reason</label>
                                            <input  type="text" id="reason" name="reason" class="form-control"
                                                   placeholder="Reason"
                                                   value="{{($editData)?$editData[0]->reason:''}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="">Description</label>
                                            <textarea  placeholder="Enter Description"
                                                      rows="3" wrap="soft"
                                                      name="description"
                                                      class="form-control"
                                                      id="instructions">{{($editData)?$editData[0]->description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <fieldset class="border mt-2 mb-2 col-md-12">
                                        <legend class="w-auto required" style="font-size: 18px;">Case Documents Upload
                                        </legend>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Case Document Name</label>
                                                    <input type="text" id="case_doc_name" name="case_doc_name"
                                                           class="form-control "
                                                           placeholder="Case Document Name" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="order_attachment" class="">Attachment</label>
                                                    <input type="file" class="form-control" id="attachedFile"
                                                           name="attachedFile" onchange="encodeFileAsURL();"/>
                                                </div>
                                                <input type="hidden" id="converted_file" name="converted_file">
                                            </div>

                                            <div class="col-md-1">
                                                <div id="start-no-field"
                                                     class="form-group @error('seat_to1') has-error @enderror">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary add-row-doc">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">

                                                    <table class="table table-striped table-bordered" id="table-doc">
                                                        <thead>
                                                        <tr>
                                                            <th style="height: 25px;text-align: left; width: 5%">#</th>
                                                            <th style="height: 25px;text-align: left; width: 5%">Sl.
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 40%">
                                                                Document Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 10%">Final Doc</th>
                                                            <th style="height: 25px;text-align: left; width: 40%">
                                                                Attachment
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody id="file_body">
                                                        @if(!empty($caseDocData))
                                                            @foreach($caseDocData as $key=>$value)
                                                                <tr>
                                                                    <td>
                                                                        <input type='checkbox' name='record'>
                                                                        <input type='hidden' name='case_doc_id[]'
                                                                               value='{{($value)?$value->case_doc_id:'0'}}'
                                                                               class="case_doc_id">
                                                                        <input type='hidden' name='case_doc_name[]'
                                                                               value='{{($value)?$value->case_doc_name:''}}'
                                                                               class="case_doc_name">
                                                                        <input type='hidden' name='case_doc[]'
                                                                               value='{{($value)?$value->case_doc:''}}'
                                                                               class="case_doc">
                                                                        <input type='hidden' name='case_doc_type[]'
                                                                               value='{{($value)?$value->case_doc_type:''}}'
                                                                               class="case_doc_type">
                                                                    </td>
                                                                    <td>{{++$key}}</td>
                                                                    <td><input type="text" class="form-control"
                                                                               name="doc_description[]"
                                                                               value="{{$value->doc_description}}"></td>
                                                                    <td>
                                                                        <select class="custom-select form-control" id="final_doc" name="final_doc[]">
                                                                            <option value="N"<?php if ($value->decree_yn == 'N') echo ' selected="selected"'; ?>>NO</option>
                                                                            <option value="Y"<?php if ($value->decree_yn == 'Y') echo ' selected="selected"'; ?>>YES</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>@if(isset($value->case_doc))
                                                                            <a href="{{ route('cms.case-info-update.case-info-update-file-download', [$value->case_doc_id]) }}"
                                                                               target="_blank">{{$value->case_doc_name}}</a>
                                                                        @endif</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    <button type="button"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-file">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                        </div>
                        <hr>

                        @if($editData)
                            <div class="row">
                                <div class="col-md-12 text-right" id="cancel">
                                    <button type="submit" id="update"
                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                        Update
                                    </button>
                                    <a href="{{url('/case-info-update')}}">
                                        <button type="button" id="cancel"
                                                class="btn btn btn-outline shadow mb-1 btn-secondary">
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12 text-right" id="add">

                                    <button type="submit" id="add"
                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Save
                                    </button>

                                    <button type="reset" id="reset"
                                            class="btn btn btn-outline shadow mb-1 btn-secondary">Reset
                                    </button>
                                </div>
                            </div>
                        @endif

                    </form>

                </div>
                <!-- Table End -->
            </div>
            @include('cms.caseinfoupdate.caseInfoUpdateList')

        </div>
    </div>

@endsection

@section('footer-script')
    <!--Load custom script-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $("#reset").click(function () {
            $("#case_info_update_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });

        function encodeFileAsURL() {
            var file = document.querySelector('input[type=file]')['files'][0];
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                $("#converted_file").val(baseString);
                console.log(baseString);
            };
            reader.readAsDataURL(file);
        }
    </script>

    <script type="text/javascript">

        function convertDate(inputFormat) {
            function pad(s) {
                return (s < 10) ? '0' + s : s;
            }

            var d = new Date(inputFormat)
            return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('-')
        }

        $('#caseDateDP').datetimepicker({
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

        $('#lastUpdateDateDP').datetimepicker({
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

        $('#nextDateDP').datetimepicker({
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

        function chkTable() {
            if ($('#file_body tr').length == 0) {
                Swal.fire({
                    title: 'Upload minimum 1 attachment!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                return true;
            }
        }

        $(document).ready(function () {
            let case_status = $('#case_status').val();
            if (case_status == '3') {
                $('#win_lose').show();
                $('#next_date').css("display", "none");
            } else {
                $('#win_lose').hide();
                $('#next_date').css("display", "block");
            }

            let case_no = ($('#case_no').val());
            if (case_no) {
                compDataList(case_no);
                defDataList(case_no);
            }
            caseUpdatelist();
            getCaseData();
            $(document).on('change', '.custom-file-input', function () {
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            $("#case_no").change(function () {
                let case_no = ($('#case_no').val());
                if (case_no !== null) {
                    $.ajax({
                        type: 'GET',
                        url: '/get-case-master-data',
                        data: {case_no: case_no},
                        success: function (msg) {
                            //console.log(msg[0].case_id)
                            console.log(msg)
                            $("#case_date").val(convertDate(msg[0].case_date));
                            $("#case_category").val(msg[0].category_name);
                            if(msg[0].next_date != null){
                                $("#last_update_date").val(convertDate(msg[0].next_date));
                            }else{
                                $("#last_update_date").val('');
                            }
                            $("#court").val(msg[0].court_name);
                            $("#description").val(msg[0].case_description);
                            $("#case_id").val(msg[0].case_id);
                            $("#case_status_id").val(msg[0].case_status_id);
                            $('#case_status').val(msg[0].case_status_id);
                            $('#case_status').trigger('change');
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: '/get-lawyer',
                        data: {case_no: case_no},
                        success: function (msg) {
                            $("#lawyer").html(msg);
                        }
                    });

                    compDataList(case_no);
                    defDataList(case_no);
                }
            });


            function compDataList(case_no) {
                var tblPreventivi = $('#searchResultTable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    bFilter: false,
                    bInfo: false,
                    bPaginate: false,
                    ajax: {
                        url: '/get-case-comp-data',
                        data: {case_no: case_no},
                    },
                    "columns": [
                        {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                        {"data": "party_details_name"},
                        {"data": "party_details_address"},
                        {"data": "party_contact_no"},
                    ],
                });
            }

            function defDataList(case_no) {
                var tblPreventivi = $('#table-def').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    bFilter: false,
                    bInfo: false,
                    bPaginate: false,
                    ajax: {
                        url: '/get-case-def-data',
                        data: {case_no: case_no},
                    },
                    "columns": [
                        {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                        {"data": "party_details_name"},
                        {"data": "party_details_address"},
                        {"data": "party_contact_no"},
                    ],
                });
            }

            $(".add-row-doc").click(function () {

                var case_doc_name = $("#case_doc_name").val();
                var converted_file = $("#converted_file").val();

                var filePath = $("#attachedFile").val();
                var file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
                var fileName = document.getElementById('attachedFile').files[0].name;

                var count;
                var file_count = parseInt(document.getElementById("file_count").value);
                if (file_count > 0) {
                    count = file_count + 1;
                } else {

                    var attach_count = parseInt(document.getElementById("attach_count").value);
                    count = attach_count + 1;
                }

                var markup1 = "<tr><td><input type='checkbox' name='record'>" +
                    "<input type='hidden' name='doc_description[]' value='" + case_doc_name + "'>" +
                    "<input type='hidden' name='case_doc_name[]' value='" + fileName + "'>" +
                    "<input type='hidden' name='case_doc_type[]' value='" + file_ext + "'>" +
                    "<input type='hidden' name='case_doc[]' value='" + converted_file + "'>" +
                    "</td><td>" + count + "</td><td>" + case_doc_name + "</td>" +
                    "<td><select class='custom-select form-control' id='final_doc' name='final_doc[]'><option value='N'>NO</option><option value='Y'>YES</option></select></td>" +
                    "<td>" + fileName + "</td></tr>";
                $("#case_doc_name").val("");
                $("#attachedFile").val("");
                $("#table-doc tbody").append(markup1);
                $("#attach_count").val(count);
            });

            $(".delete-row-file").click(function () {
                $("#table-doc tbody").find('input[name="record"]').each(function () {
                    if ($(this).is(":checked")) {
                        let case_doc_id = $(this).closest('tr').find('.case_doc_id').val();
                        if (case_doc_id !== null) {
                            $(this).parents("tr").remove();
                            $.ajax({
                                type: 'GET',
                                url: '/case-update-doc-remove',
                                data: {case_doc_id: case_doc_id},
                                success: function (msg) {
                                    $(this).parents("tr").remove();
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        //location.reload();

                                    });
                                }
                            });
                        } else {
                            $(this).parents("tr").remove();
                        }
                        $("#attach_count").val('0');
                    }
                });
            });

        });

        function getCaseData() {
            $('#case_no').select2({
                placeholder: "Select",
                allowClear: true,
                ajax: {
                    url: '/get-case-data',
                    data: function (params) {
                        if (params.term) {
                            if (params.term.trim().length < 1) {
                                return false;
                            }
                        } else {
                            return false;
                        }

                        return params;
                    },
                    dataType: 'json',
                    processResults: function (data) {
                        var formattedResults = $.map(data, function (obj, idx) {
                            obj.id = obj.case_id;
                            obj.text = obj.case_no;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    }
                }
            });
        }

        function caseUpdatelist() {
            $('#case_update_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/case-info-update-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'case_no', "name": 'case_no'},
                    {"data": 'category_name', "name": 'category_name'},
                    {"data": 'court_name', "name": 'court_name'},
                    {"data": 'lawyer_name', "name": 'lawyer_name'},
                    {"data": 'case_update_date', "name": 'case_update_date'},
                    {"data": 'next_date', "name": 'next_date'},
                    {"data": 'reason', "name": 'reason'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }

        $("#case_status").change(function () {
            let case_status = ($('#case_status').val());
            if (case_status == '3') {
                $('#win_lose').show();
                $('#next_date').css("display", "none");
            } else {
                $('#win_lose').hide();
                $('#next_date').css("display", "block");
            }
        });
    </script>


@endsection


