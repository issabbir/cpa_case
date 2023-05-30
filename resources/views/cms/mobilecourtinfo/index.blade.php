@extends('layouts.default')

@section('title')

@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(Session::has('message'))
                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-content">
                            <div class="card-body">
                                <form enctype="multipart/form-data"
                                      @if(isset($data->case_id)) action="{{route('cms.mobile-court-info.mobile-court-update',[$data->case_id])}}"
                                      @else action="{{route('cms.mobile-court-info.mobile-court-post')}}"
                                      @endif method="post">
                                    @csrf
                                    @if (isset($data->case_id))
                                        @method('PUT')
                                        <input type="hidden" id="case_id" name="case_id"
                                               value="{{isset($data->case_id) ? $data->case_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Mobile Court Information</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Case No</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Case No"
                                                   name="case_no" @if(isset($data->case_no)) readonly @endif
                                                   class="form-control" required
                                                   value="{{isset($data->case_no) ? $data->case_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Case Nothi No</label>
                                            <input type="text" autocomplete="off"
                                                   @if(isset($data->case_nothi_no)) readonly @endif
                                                   placeholder="Case Nothi No"
                                                   name="case_nothi_no"
                                                   class="form-control"
                                                   value="{{isset($data->case_nothi_no) ? $data->case_nothi_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="300"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Case Date:</label>
                                            <div class="input-group date" id="datetimepicker"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($data->case_date) ? date('d-m-Y', strtotime($data->case_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker"
                                                       id="case_date"
                                                       name="case_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Case Status</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="case_status_id" name="case_status_id">
                                                <option value="">Select One</option>
                                                @foreach($caseStatusList as $value)
                                                    <option value="{{$value->case_status_id}}"
                                                        {{isset($data->case_status_id) && $data->case_status_id == $value->case_status_id ? 'selected' : ''}}
                                                    >{{$value->show_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label class="required">DESCRIPTION</label>
                                            <textarea rows="3" wrap="soft" name="case_description" class="form-control"
                                                      id="case_description">{{isset($data->case_description) ? $data->case_description : ''}}</textarea>
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>DESCRIPTION(Bangla)</label>
                                            <textarea rows="3" wrap="soft" name="case_description_bng"
                                                      class="form-control"
                                                      id="case_description_bng">{{isset($data->case_description_bng) ? $data->case_description_bng : ''}}</textarea>
                                        </div>

                                        {{--<div class="col-md-3 mt-1">
                                            <label class="required">Case Category</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="category_id" name="category_id">
                                                <option value="">Select One</option>
                                                @foreach($caseCategoryList as $value)
                                                    <option value="{{$value->category_id}}"
                                                        {{isset($data->category_id) && $data->category_id == $value->category_id ? 'selected' : ''}}
                                                    >{{$value->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Court</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="court_id" name="court_id">
                                                <option value="">Select One</option>
                                                @foreach($courtList as $value)
                                                    <option value="{{$value->court_id}}"
                                                        {{isset($data->court_id) && $data->court_id == $value->pass_value ? 'selected' : ''}}
                                                    >{{$value->show_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Court Category</label>
                                            <select class="custom-select select2 form-control" readonly
                                                    id="court_category_id" name="court_category_id">
                                                <option value="">Select One</option>
                                                @foreach($courtCategoryList as $value)
                                                    <option value="{{$value->court_category_id}}"
                                                        {{isset($data->court_category_id) && $data->court_category_id == $value->court_category_id ? 'selected' : ''}}
                                                    >{{$value->court_category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Division</label>
                                            <select class="custom-select select2 form-control" readonly
                                                    id="division_id" name="division_id">
                                                <option value="">Select One</option>
                                                @foreach($divisionList as $value)
                                                    <option value="{{$value->geo_division_id}}"
                                                        {{$value->geo_division_id == 2 ? 'selected' : ''}}
                                                    >{{$value->geo_division_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>--}}
                                        <div class="col-md-3 mt-1">
                                            <label class="required">District</label>
                                            <select class="custom-select select2 form-control" readonly
                                                    id="district_id" name="district_id">
                                                {{--<option value="">Select One</option>--}}
                                                {{--@if(!empty($districtList))
                                                    @foreach($districtList as $value)
                                                        <option value="{{$value->geo_district_id}}"
                                                            {{isset($data->district_id) && $data->district_id == $value->geo_district_id ? 'selected' : ''}}
                                                        >{{$value->geo_district_name}}</option>
                                                    @endforeach
                                                @endif--}}
                                                @if(isset($districtList))
                                                    @foreach($districtList as $option)
                                                        {!!$option!!}
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Thana</label>
                                            <select class="custom-select select2 form-control" readonly
                                                    id="thana_id" name="thana_id">
                                                {{--<option value="">Select One</option>
                                                @if(!empty($thanaList))
                                                    @foreach($thanaList as $value)
                                                        <option value="{{$value->geo_thana_id}}"
                                                            {{isset($data->thana_id) && $data->thana_id == $value->geo_thana_id ? 'selected' : ''}}
                                                        >{{$value->geo_thana_name}}</option>
                                                    @endforeach
                                                @endif--}}
                                                @if(isset($thanaList))
                                                    @foreach($thanaList as $option)
                                                        {!!$option!!}
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Prosecutor</label>
                                            <select class="custom-select select2 form-control prosecutor_emp_id"
                                                    id="prosecutor_emp_id" name="prosecutor_emp_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->prosecutor_emp_id}}">{{$data->prosecutor_emp_code.'-'.$data->prosecutor_name.''}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Convicted Law</label><a href="javascript:void(0)"
                                                                                            style="font-size: 12px;padding-left: 5px;"
                                                                                            id="add_law">|| ADD NEW</a>
                                            <select class="custom-select select2 form-control convicted_law_id" required
                                                    id="convicted_id" name="convicted_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->convicted_id}}">{{$data->convicted_name}}</option>
                                                @endif
                                                {{--<option value="">Select One</option>
                                                @foreach($convictedLaw as $value)
                                                    <option value="{{$value->convicted_id}}"
                                                        {{isset($data->convicted_id) && $data->convicted_id == $value->convicted_id ? 'selected' : ''}}
                                                    >{{$value->convicted_name}}</option>
                                                @endforeach--}}
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="mb-1">Guilty Person From CPA?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="guilty_person_emp_yn" id="active_no"
                                                               onclick="javascript:noCheck();" checked
                                                               value="{{ \App\Enums\YesNoFlag::NO }}"
                                                               @if(isset($data->guilty_person_emp_yn) && $data->guilty_person_emp_yn == "N") checked @endif/>
                                                        <label class="form-check-label">NO</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="guilty_person_emp_yn"
                                                               onclick="javascript:yesCheck();"
                                                               id="active_yes" value="{{ \App\Enums\YesNoFlag::YES }}"
                                                               @if(isset($data->guilty_person_emp_yn) && $data->guilty_person_emp_yn == "Y") checked @endif/>
                                                        <label class="form-check-label">YES</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1" id="show_drop">
                                            <label class="required">Guilty Person</label>
                                            <select class="custom-select select2 form-control guilty_person_emp_id"
                                                    id="guilty_person_emp_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->guilty_person_emp_id}}">{{$data->guilty_person_emp_code.'-'.$data->guilty_person_name.''}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1" id="show_text">
                                            <label class="required">Guilty Person Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Guilty Person Name"
                                                   id="guilty_person_name"
                                                   class="form-control"
                                                   value="{{isset($data->guilty_person_name) ? $data->guilty_person_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
                                            >
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label>Guilty Person Address</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Guilty Person Address"
                                                   name="victim_address"
                                                   class="form-control"
                                                   value="{{isset($data->victim_address) ? $data->victim_address : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
                                            >
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label class="required">Court Place</label><a href="javascript:void(0)"
                                                                                          style="font-size: 12px;padding-left: 5px;"
                                                                                          id="add_place">|| ADD NEW</a>
                                            <select class="custom-select select2 form-control court_place_id_get"
                                                    required
                                                    id="court_place_id" name="court_place_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->court_place_id}}">{{$data->court_place_name}}</option>
                                                @endif
                                                {{--<option value="">Select One</option>
                                                @foreach($courtPlace as $value)
                                                    <option value="{{$value->court_place_id}}"
                                                        {{isset($data->court_place_id) && $data->court_place_id == $value->court_place_id ? 'selected' : ''}}
                                                    >{{$value->court_place_name}}</option>
                                                @endforeach--}}
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="mb-1">EVICTION?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="eviction_yn" id="eviction_yn_no" checked
                                                               onclick="javascript:evictionNo();"
                                                               value="{{ \App\Enums\YesNoFlag::NO }}"
                                                               @if(isset($data->eviction_yn) && $data->eviction_yn == "N") checked @endif/>
                                                        <label class="form-check-label">NO</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="eviction_yn"
                                                               onclick="javascript:evictionYes();"
                                                               id="eviction_yn_yes" value="{{ \App\Enums\YesNoFlag::YES }}"
                                                               @if(isset($data->eviction_yn) && $data->eviction_yn == "Y") checked @endif/>
                                                        <label class="form-check-label">YES</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1" id="hide_fine">
                                            <label class="required">Fine Amount</label>
                                            <input type="number" autocomplete="off"
                                                   placeholder="Fine Amount" required
                                                   name="fines_amount"
                                                   id="fines_amount"
                                                   class="form-control"
                                                   value="{{isset($data->fines_amount) ? $data->fines_amount : ''}}"
                                            >
                                        </div>

                                        <div class="col-md-3 mt-1" id="hide_imprisonment">
                                            <label>IMPRISONMENT</label>
                                            <div class="form-group">
                                                <input type="number" placeholder="Year"
                                                       value="{{isset($data->imprisonment_year) ? $data->imprisonment_year : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="imprisonment_year" id="imprisonment_year">
                                                {{--<span>Year</span>--}}
                                                <input type="number" placeholder="Month"
                                                       value="{{isset($data->imprisonment_month) ? $data->imprisonment_month : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="imprisonment_month" id="imprisonment_month">
                                                {{--<span>Month</span>--}}
                                                <input type="number" placeholder="Day"
                                                       value="{{isset($data->imprisonment_day) ? $data->imprisonment_day : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="imprisonment_day" id="imprisonment_day">
                                                {{--<span>Day</span>--}}
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1" id="hide_unpaid_imprisonment">
                                            <label>UNPAID IMPRISONMENT</label>
                                            <div class="form-group">
                                                <input type="number" placeholder="Year"
                                                       value="{{isset($data->unpaid_imprisonment_year) ? $data->unpaid_imprisonment_year : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="unpaid_imprisonment_year" id="unpaid_imprisonment_year">
                                                {{--<span>Year</span>--}}
                                                <input type="number" placeholder="Month"
                                                       value="{{isset($data->unpaid_imprisonment_month) ? $data->unpaid_imprisonment_month : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="unpaid_imprisonment_month" id="unpaid_imprisonment_month">
                                                {{--<span>Month</span>--}}
                                                <input type="number" placeholder="Day"
                                                       value="{{isset($data->unpaid_imprisonment_day) ? $data->unpaid_imprisonment_day : ''}}"
                                                       style="height: 37px;width: 80px; vertical-align: middle;"
                                                       name="unpaid_imprisonment_day" id="unpaid_imprisonment_day">
                                                {{--<span>Day</span>--}}
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label class="required">MAGISTRATE</label>
                                            <select class="custom-select select2 form-control magistrate_emp_id"
                                                    id="magistrate_emp_id" name="magistrate_emp_id">
                                                @if(isset($data))
                                                    <option
                                                        value="{{$data->magistrate_emp_id}}">{{$data->magistrate_emp_code.'-'.$data->magistrate_name.''}}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-1">
                                            <label>Comment</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Comment"
                                                   name="comments"
                                                   class="form-control"
                                                   value="{{isset($data->comments) ? $data->comments : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="4000"
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($data))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                @endif

                                                <a type="reset"
                                                   href="{{route("cms.mobile-court-info.mobile-court-index")}}"
                                                   class="btn btn-light-secondary mb-1"> Back</a>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Convicted Law</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form role="form-horizontal" action="" id="add_law_form">
                        <div class="row">
                            <div class="col-md-4 mt-1">
                                <label class="required">CONVICTED NAME</label>
                                <input type="text" autocomplete="off"
                                       placeholder="Convicted Name"
                                       name="convicted_name"
                                       id="convicted_name"
                                       class="form-control"
                                >
                            </div>
                            <div class="col-md-4 mt-1">
                                <label>CONVICTED NAME(BANGLA)</label>
                                <input type="text" autocomplete="off"
                                       placeholder="Convicted Name(Bangla)"
                                       name="convicted_name_bng"
                                       id="convicted_name_bng"
                                       class="form-control"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right" id="add">
                                <button type="submit" id="add"
                                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Save
                                </button>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Court Place</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form role="form-horizontal" action="" id="add_place_form">
                        <div class="row">
                            <div class="col-md-4 mt-1">
                                <label class="required">Court Place Name</label>
                                <input type="text" autocomplete="off"
                                       placeholder="Court Place Name"
                                       name="court_place_name"
                                       id="court_place_name"
                                       class="form-control"
                                >
                            </div>
                            <div class="col-md-4 mt-1">
                                <label>Court Place NAME(BANGLA)</label>
                                <input type="text" autocomplete="off"
                                       placeholder="Court Place Name(Bangla)"
                                       name="court_place_name_bng"
                                       id="court_place_name_bng"
                                       class="form-control"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right" id="add">
                                <button type="submit" id="add"
                                        class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Save
                                </button>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @include('cms.mobilecourtinfo.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function evictionYes() {
            if (document.getElementById('eviction_yn_yes').checked) {//fines_amount
                document.getElementById("fines_amount").required = false;
                $('#hide_fine').css("display", "none");
                $('#hide_imprisonment').css("display", "none");
                $('#hide_unpaid_imprisonment').css("display", "none");

                $('#fines_amount').val('');
                $('#imprisonment_year').val('');
                $('#imprisonment_month').val('');
                $('#imprisonment_day').val('');
                $('#unpaid_imprisonment_year').val('');
                $('#unpaid_imprisonment_month').val('');
                $('#unpaid_imprisonment_day').val('');
            }
        }

        function evictionNo() {
            if (document.getElementById('eviction_yn_no').checked) {
                document.getElementById("fines_amount").required = true;
                $('#hide_fine').css("display", "block");
                $('#hide_imprisonment').css("display", "block");
                $('#hide_unpaid_imprisonment').css("display", "block");

                $('#fines_amount').val('');
                $('#imprisonment_year').val('');
                $('#imprisonment_month').val('');
                $('#imprisonment_day').val('');
                $('#unpaid_imprisonment_year').val('');
                $('#unpaid_imprisonment_month').val('');
                $('#unpaid_imprisonment_day').val('');
            }
        }

        function yesCheck() {
            if (document.getElementById('active_yes').checked) {
                $('#show_drop').css("display", "block");
                $('#show_text').css("display", "none");
                $('#guilty_person_emp_id').attr('name', 'guilty_person_emp_id');
                $('#guilty_person_name').val('');
                $('#guilty_person_emp_id').empty();
            }
        }

        function noCheck() {
            if (document.getElementById('active_no').checked) {
                $('#show_drop').css("display", "none");
                $('#show_text').css("display", "block");
                $('#guilty_person_name').attr('name', 'guilty_person_name');
                $('#guilty_person_name').val('');
                $('#guilty_person_emp_id').empty();
            }
        }

        $("#add_law").click(function () {
            var myModal = $('#exampleModal');
            myModal.modal({show: true});
            return false;
        });

        $("#add_place").click(function () {
            var myModal = $('#exampleModal1');
            myModal.modal({show: true});
            return false;
        });

        $("#add_law_form").submit(function (event) {
            event.preventDefault();
            var convicted_name = $('#convicted_name').val();
            var convicted_name_bng = $('#convicted_name_bng').val();

            $.ajax({
                type: "GET",
                data: {
                    convicted_name: convicted_name,
                    convicted_name_bng: convicted_name_bng,
                },
                url: '/add-convicted-law',
                success: function (data) {
                    alert(data['o_status_message']);
                    $('#exampleModal').modal('hide');
                    $("#add_law_form").trigger('reset');
                },
                error: function (data) {
                    alert('error');
                }
            });
        });

        $("#add_place_form").submit(function (event) {
            event.preventDefault();
            var court_place_name = $('#court_place_name').val();
            var court_place_name_bng = $('#court_place_name_bng').val();

            $.ajax({
                type: "GET",
                data: {
                    court_place_name: court_place_name,
                    court_place_name_bng: court_place_name_bng,
                },
                url: '/add-court-place',
                success: function (data) {
                    alert(data['o_status_message']);
                    $('#exampleModal1').modal('hide');
                    $("#add_place_form").trigger('reset');
                },
                error: function (data) {
                    alert('error');
                }
            });
        });

        /*$('#division_id').change(function () {
            var division = $(this).val();
            $.ajax({
                type: 'get',
                url: '/get-district-ajax',
                data: {division: division},
                success: function (msg) {
                    $("#district_id").html(msg);
                }
            });
        });*/

        $('#district_id').change(function () {
            var district = $(this).val();
            $.ajax({
                type: 'get',
                url: '/get-thana-ajax',
                data: {district: district},
                success: function (msg) {
                    $("#thana_id").html(msg);
                }
            });
        });

        $('.court_place_id_get').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-court-place',
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
                        obj.id = obj.court_place_id;
                        obj.text = obj.court_place_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.convicted_law_id').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-law',
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
                        obj.id = obj.convicted_id;
                        obj.text = obj.convicted_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.prosecutor_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-employee',
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
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_code + '-' + obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.guilty_person_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-employee',
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
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_code + '-' + obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.magistrate_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-employee',
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
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_code + '-' + obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        function datePicker(selector) {
            var elem = $(selector);
            elem.datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
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
            let preDefinedDate = elem.attr('data-predefined-date');

            if (preDefinedDate) {
                let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD").format("YYYY-MM-DD");
                elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }
        }

        function caseList() {
            var url = '{{route('cms.mobile-court-info.mobile-court-datatable')}}';
            var oTable = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'case_no', name: 'case_no', searchable: true},
                    {data: 'case_nothi_no', name: 'case_nothi_no', searchable: true},
                    {data: 'case_date', name: 'case_date', searchable: true},
                    {data: 'prosecutor_name', name: 'prosecutor_name', searchable: true},
                    {data: 'guilty_person_name', name: 'guilty_person_name', searchable: true},
                    {data: 'fines_amount', name: 'fines_amount', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            var schedule_mst_id = '{{request()->get('id')}}';

            if (schedule_mst_id) {
                var division = 2;
                $.ajax({
                    type: 'get',
                    url: '/get-district-ajax',
                    data: {division: division},
                    success: function (msg) {
                        $("#district_id").html(msg);
                    }
                });
            }

            @if(isset($data->guilty_person_emp_yn))
                @if($data->guilty_person_emp_yn == 'N')
                    $('#guilty_person_name').attr('name', 'guilty_person_name');
                    $('#show_drop').css("display", "none");
                    $('#show_text').css("display", "block");
                @elseif($data->guilty_person_emp_yn == 'Y')
                    $('#guilty_person_emp_id').attr('name', 'guilty_person_emp_id');
                    $('#show_drop').css("display", "block");
                    $('#show_text').css("display", "none");
                @endif
            @else
                $('#guilty_person_name').attr('name', 'guilty_person_name');
                $('#show_drop').css("display", "none");
                $('#show_text').css("display", "block");
            @endif


            datePicker('#datetimepicker');
            caseList();
        });

    </script>

@endsection

