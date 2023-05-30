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
                    <h4 class="card-title">Case Information</h4><!---->
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
                    <form role="form-horizontal" enctype="multipart/form-data" id="case_info_form"
                          action="{{($editData)?url('/case-info/edit/'.$editData['case_id']):url('/case-info')}}"
                          method="post" onsubmit="return chkTable()">

                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Case Status</label>
                                            <select required class="custom-select select2" name="case_status"
                                                    id="case_status">
                                                {{--<option value="">-- Please select an option --</option>--}}
                                                @foreach($caseStatusList as $caseStatus)
                                                    {{--<option value="{{$caseStatus->case_status_id}}"
                                                    @if(!empty($editData)) @if($caseStatus->case_status_id == $editData['case_status_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$caseStatus->show_value}}</option>--}}
                                                    <option value="{{$caseStatus->case_status_id}}" {{ old('case_status') == $caseStatus->case_status_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($caseStatus->case_status_id == $editData['case_status_id']) {{'selected="selected"'}} @endif @endif>{{$caseStatus->show_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="prev_case" style="@if(!empty($editData['previous_case_id'])) display: block @else display: none @endif">
                                        <div class="form-group">
                                            <label>Previous Case No</label>
                                            <select class="custom-select select2" name="prev_case_no"
                                                    id="prev_case_no">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($caseData as $case)
                                                    {{--<option value="{{$case->case_id}}{{ old('prev_case_no') == $case->case_id ? 'selected' : '' }}">{{$case->case_no}}</option>--}}
                                                    <option value="{{$case->case_id}}" {{ old('prev_case_no') == $case->case_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($case->case_id == $editData['previous_case_id']) {{'selected="selected"'}} @endif @endif>{{$case->case_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{--@if(!empty($editData['previous_case_id']))
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Previous Case No</label>
                                                <select class="custom-select select2" name="prev_case_no"
                                                        id="prev_case_no">
                                                    <option value="">-- Please select an option --</option>
                                                    @foreach($caseData as $case)
                                                        --}}{{--<option value="{{$case->case_id}}"
                                                        @if(!empty($editData)) @if($case->case_id == $editData['previous_case_id']) {{'selected="selected"'}} @endif @endif
                                                        >{{$case->case_no}}</option>--}}{{--

                                                        <option value="{{$case->case_id}}" {{ old('prev_case_no') == $case->case_id ? 'selected' : '' }}
                                                        @if(!empty($editData)) @if($case->case_id == $editData['previous_case_id']) {{'selected="selected"'}} @endif @endif>{{$case->case_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">New Case No.</label>
                                            <input required type="text" id="case_no" name="case_no" class="form-control"
                                                   placeholder="Case No."
                                                   value="{{ old('case_no',($editData)?$editData['case_no']:'') }}"
                                                   autocomplete="off">
                                            @if(!empty($editData))
                                                <input type="hidden" id="case_id" name="case_id" class="form-control"
                                                       value="{{($editData)?$editData['case_id']:''}}">
                                            @endif
                                            <input type="hidden" name="comp_count" value="0" id="comp_count">
                                            <input type="hidden" name="def_count" value="0" id="def_count">
                                            <input type='hidden'
                                                   value='{{($complainantData)?$complainantData->count():'0'}}'
                                                   id="complainent_count">
                                            <input type='hidden'
                                                   value='{{($defendentData)?$defendentData->count():'0'}}'
                                                   id="defndent_count">
                                            <input type='hidden' value='{{($caseDocData)?count($caseDocData):'0'}}'
                                                   id="file_count">
                                            <input type='hidden' value='0' id="attach_count">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Case Date</label>
                                            <div class="input-group date" id="caseDateDP" data-target-input="nearest">
                                                <input type="text"
                                                       value="{{old('case_date', ($editData && $editData['case_date'])?date('d-m-Y', strtotime($editData['case_date'])):'')}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#caseDateDP"
                                                       id="case_date"
                                                       name="case_date"
                                                       placeholder="Case Date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Category</label>
                                            <select class="custom-select select2" name="case_category"
                                                    id="case_category">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($caseCategoryList as $Category)
                                                    {{--<option value="{{$Category->category_id}}"
                                                    @if(!empty($editData)) @if($Category->category_id == $editData['category_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$Category->category_name}}</option>--}}

                                                    <option value="{{$Category->category_id}}" {{ old('case_category') == $Category->category_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($Category->category_id == $editData['category_id']) {{'selected="selected"'}} @endif @endif>{{$Category->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Court</label>
                                            <select required class="custom-select select2" name="court_id"
                                                    id="court_id">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($courtList as $court)
                                                    {{--<option value="{{$court->court_id}}"
                                                    @if(!empty($editData)) @if($court->court_id == $editData['court_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$court->show_value}}</option>--}}

                                                    <option value="{{$court->court_id}}" {{ old('court_id') == $court->court_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($court->court_id == $editData['court_id']) {{'selected="selected"'}} @endif @endif>{{$court->show_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Department</label>
                                            <select required class="custom-select select2" name="authorise_officer_dept"
                                                    id="authorise_officer_dept">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($authOfficerDeptList as $data)
                                                    {{--<option value="{{$data->department_id}}"
                                                    @if(!empty($editData)) @if($data->department_id == $editData['auth_dept_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$data->department_name}}</option>--}}


                                                    <option value="{{$data->department_id}}" {{ old('authorise_officer_dept') == $data->department_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($data->department_id == $editData['auth_dept_id']) {{'selected="selected"'}} @endif @endif>{{$data->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Authorised Officer</label>
                                            <select class="custom-select select2" name="authorise_officer_id"
                                                    id="authorise_officer_id">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($authOfficerList as $Category)
                                                    {{--<option value="{{$Category->emp_id}}"
                                                    @if(!empty($editData)) @if($Category->emp_id == $editData['authorise_officer']) {{'selected="selected"'}} @endif @endif
                                                    >{{$Category->show_name}}</option>--}}

                                                    <option value="{{$Category->emp_id}}" {{ old('authorise_officer_id') == $Category->emp_id ? 'selected' : '' }}
                                                    @if(!empty($editData)) @if($Category->emp_id == $editData['authorise_officer']) {{'selected="selected"'}} @endif @endif>{{$Category->show_name}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="form-group">
                                            <label>
                                                @if(isset($editData) && !empty($editData))

                                                    @if($editData['pro_former_yn'] == 'Y')
                                                        <input type="checkbox" style="height: 37px;width: 37px; vertical-align: middle;" name="pro_former_defender" id="pro_former_defender" checked>
                                                    @else
                                                        <input type="checkbox" style="height: 37px;width: 37px; vertical-align: middle;" name="pro_former_defender" id="pro_former_defender">
                                                    @endif
                                                @else
                                                    <input type="checkbox" style="height: 37px;width: 37px; vertical-align: middle;" name="pro_former_defender" id="pro_former_defender">
                                                @endif
                                            <span>PRO Forma Defendant</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
{{--                                            <div class="checkbox">--}}
                                            <label class="required">Description</label>
                                            <textarea required placeholder="Enter Description"
                                                      rows="3" wrap="soft"
                                                      name="description"
                                                      class="form-control"
                                                      id="instructions">{{old('description',($editData)?$editData['case_description']:'')}}</textarea>

{{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <fieldset class="border p-2 col-sm-12">
                                        <legend class="w-auto required" style="font-size: 18px;">
                                            Complainant/Petitioner
                                        </legend>
{{--                                        <div class="row">--}}
{{--                                            <div class="col-12 d-flex justify-content-end">--}}
{{--                                                <button type="button" id="append"--}}
{{--                                                        class="btn btn btn-dark shadow mr-1 mt-1 btn-secondary add-org-comp">--}}
{{--                                                    Add Organization--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="mb-1">Organization</label>
                                                    <select class="custom-select form-control"
                                                            id="comp_chk_org" name="comp_chk_org">
                                                        <option value="">Select One</option>
                                                        <option value="{{ \App\Enums\YesNoFlag::YES }}">
                                                            Yes
                                                        </option>
                                                        <option value="{{ \App\Enums\YesNoFlag::NO }}">
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="display: none" id="complain_from_div">
                                                <div class="form-group">
                                                    <label class="mb-1">Complain from</label>
                                                    <select class="custom-select form-control"
                                                            id="comp_chk_complain_from"
                                                            name="comp_chk_complain_from">
                                                        <option value="">Select One</option>
                                                        <option value="O">
                                                            Outsider
                                                        </option>
                                                        <option value="C">
                                                            CPA
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="ifYes" style="display: none">
                                                <div class="form-group">
                                                    <label class="mb-1">Employee Code</label>
                                                    <select class="custom-select select2"
                                                            id="cpa_reporter_emp_id">

                                                    </select>
                                                </div>
                                            </div>
{{--                                            <div class="col-md-3 ml-auto text-right" style="display: none;" id="add_org_div">--}}
{{--                                                <div id="start-no-field" class="form-group">--}}
{{--                                                    <label for="add_org">&nbsp;</label><br/>--}}
{{--                                                    <button type="button" id="add_org"--}}
{{--                                                            class="btn btn btn-dark shadow mt-1 btn-secondary">--}}
{{--                                                        ADD ORGANIZATION--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                        <hr>
                                        <div class="row" style="display: none" id="with_org_comp_data">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Organization</label><a href="javascript:void(0)" style="font-size: 12px;padding-left: 5px;" id="add_org">|| ADD NEW</a>
                                                    <select class="custom-select select2" name="comp_org_name"
                                                            id="comp_org_name">
{{--                                                        <option value="">Select</option>--}}
{{--                                                        @foreach($orgList as $value)--}}
{{--                                                            <option value="{{$value->organization_id}}">{{$value->organization_name}}</option>--}}
{{--                                                        @endforeach--}}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Person Name</label>
                                                    <div id="comp_name_div">
                                                        <select class="custom-select select2" name="comp_name"
                                                                id="comp_name">
    {{--                                                        <option value="">Select</option>--}}
    {{--                                                        @foreach($orgList as $value)--}}
    {{--                                                            <option value="{{$value->organization_id}}">{{$value->organization_name}}</option>--}}
    {{--                                                        @endforeach--}}
                                                        </select>
                                                    </div>
                                                    <div id="comp_input_div">
                                                        <input type="text" id="comp_name_input" name="comp_name_input"
                                                               class="form-control "
                                                               placeholder="Person Name" value="" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Designation</label>
                                                    <input type="text" id="comp_desig" name="comp_desig"
                                                           class="form-control "
                                                           placeholder="Designation" value="" autocomplete="off">
                                                    <input type="hidden" id="comp_desig_id" name="comp_desig_id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Address</label>
                                                    <input type="text" id="comp_address" name="comp_address"
                                                           class="form-control "
                                                           placeholder="Address" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Contact No.</label>
                                                    <input type="text" id="comp_contact_no" name="comp_contact_no"
                                                           class="form-control "
                                                           placeholder="Contact No." value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1" align="right">
                                                <div id="start-no-field"
                                                     class="form-group">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary add-row-comp">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mt-2">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" id="table-comp">
                                                        <thead>
                                                        <tr>
                                                            <th style="height: 25px;text-align: left; width: 5%">#</th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Organization Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Person Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 15%">
                                                                Designation
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Address
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Contact No
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody id="comp_body">
                                                        @if(!empty($complainantData))
                                                            @foreach($complainantData as $key=>$Data)
                                                                <tr>
                                                                    <td>
                                                                        <input type='checkbox' name='record'>
                                                                        <input type='hidden' name='comp_org_name[]'
                                                                               value='{{$Data->organization_name}}'>
                                                                        <input type='hidden' name='comp_name[]'
                                                                               value='{{$Data->party_details_name}}'>
                                                                        <input type='hidden' name='comp_desig[]'
                                                                               value='{{$Data->designation}}'>
                                                                        <input type='hidden' name='comp_desig_id[]'
                                                                               value='{{$Data->designation_id}}'>
                                                                        <input type='hidden' name='comp_org_id[]'
                                                                               value='{{$Data->organization_id}}'>
                                                                        <input type='hidden' name='comp_address[]'
                                                                               value='{{$Data->party_details_address}}'>
                                                                        <input type='hidden' name='comp_contact_no[]'
                                                                               value='{{$Data->party_contact_no}}'>
                                                                        <input type='hidden' name='comp_emp_code[]'
                                                                               value='{{$Data->party_emp_id}}'>
                                                                        <input type='hidden' name='comp_chk_complain_from[]'
                                                                               value='{{$Data->org_from}}'>
                                                                        <input type='hidden'
                                                                               name='comp_party_details_id[]'
                                                                               value='{{($Data)?$Data->party_details_id:'0'}}'
                                                                               class="comp_party_details_id">
                                                                    </td>
                                                                    <td>{{$Data->organization_name}}</td>
                                                                    <td>{{$Data->party_details_name}}</td>
                                                                    <td>{{$Data->designation}}</td>
                                                                    <td>{{$Data->party_details_address}}</td>
                                                                    <td>{{$Data->party_contact_no}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @if(!empty(old('comp_org_name')))
                                                                @php $i=1; @endphp
                                                                @foreach(old('comp_org_name') as $key=>$Data)
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' name='record'>
                                                                            <input type='hidden' name='comp_org_name[]'
                                                                                   value='{{isset($Data) ? $Data : ''}}'>
                                                                            <input type='hidden' name='comp_name[]'
                                                                                   value='{{isset(old('comp_name')[$key]) ? old('comp_name')[$key] : ''}}'>
                                                                            <input type='hidden' name='comp_desig[]'
                                                                                   value='{{isset(old('comp_desig')[$key]) ? old('comp_desig')[$key] : ''}}'>
                                                                            <input type='hidden' name='comp_desig_id[]'
                                                                                   value='{{isset(old('comp_desig_id')[$key]) ? old('comp_desig_id')[$key] : ''}}'>
                                                                            <input type='hidden' name='comp_org_id[]'
                                                                                   value='{{isset(old('comp_org_id')[$key]) ? old('comp_org_id')[$key] : ''}}'>
                                                                            <input type='hidden' name='comp_address[]'
                                                                                   value='{{isset(old('comp_address')[$key]) ? old('comp_address')[$key] : ''}}'>
                                                                            <input type='hidden' name='comp_contact_no[]'
                                                                                   value='{{isset(old('comp_contact_no')[$key]) ? old('comp_contact_no')[$key] : ''}}'>
                                                                        </td>
                                                                        <td>{{isset($Data) ? $Data : ''}}</td>
                                                                        <td>{{isset(old('comp_name')[$key]) ? old('comp_name')[$key] : ''}}</td>
                                                                        <td>{{isset(old('comp_desig')[$key]) ? old('comp_desig')[$key] : ''}}</td>
                                                                        <td>{{isset(old('comp_address')[$key]) ? old('comp_address')[$key] : ''}}</td>
                                                                        <td>{{isset(old('comp_contact_no')[$key]) ? old('comp_contact_no')[$key] : ''}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    <button type="button"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-comp">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>

                                <div class="row">
                                    <fieldset class="border p-2 col-sm-12">
                                        <legend class="w-auto required" style="font-size: 18px;">Defendant/Respondent
                                        </legend>
{{--                                        <div class="row">--}}
{{--                                            <div class="col-12 d-flex justify-content-end">--}}
{{--                                                <button type="button" id="append"--}}
{{--                                                        class="btn btn btn-dark shadow mr-1 mt-1 btn-secondary add-org-def">--}}
{{--                                                    Add Organization--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="mb-1">Organization</label>
                                                    <select class="custom-select form-control"
                                                            id="def_chk_org" name="def_chk_org">
                                                        <option value="">Select One</option>
                                                        <option value="{{ \App\Enums\YesNoFlag::YES }}">
                                                            Yes
                                                        </option>
                                                        <option value="{{ \App\Enums\YesNoFlag::NO }}">
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="display: none" id="def_complain_from_div">
                                                <div class="form-group">
                                                    <label class="mb-1">Complain from</label>
                                                    <select class="custom-select form-control"
                                                            id="def_chk_complain_from"
                                                            name="def_chk_complain_from">
                                                        <option value="">Select One</option>
                                                        <option value="O">
                                                            Outsider
                                                        </option>
                                                        <option value="C">
                                                            CPA
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="ifYes1" style="display: none">
                                                <div class="form-group">
                                                    <label class="mb-1">Employee Code</label>
                                                    <select class="custom-select select2"
                                                            id="cpa_reporter_emp_id1">

                                                    </select>
                                                </div>
                                            </div>
{{--                                            <div class="col-md-3 ml-auto text-right" style="display: none;" id="add_org_div_def">--}}
{{--                                                <div id="start-no-field" class="form-group">--}}
{{--                                                    <label for="add_org_def">&nbsp;</label><br/>--}}
{{--                                                    <button type="button" id="add_org_def"--}}
{{--                                                            class="btn btn btn-dark shadow mt-1 btn-secondary">--}}
{{--                                                        ADD ORGANIZATION--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                        <hr>
                                        <div class="row" style="display: none" id="with_org_def_data">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Organization</label><a href="javascript:void(0)" style="font-size: 12px;padding-left: 5px;" id="add_org_def">|| ADD NEW</a>
                                                    <select class="custom-select select2" name="def_org_name"
                                                            id="def_org_name">
                                                    </select>
                                                    {{--<select class="custom-select" name="def_org_name"
                                                            id="def_org_name">
                                                        <option value="">-- Please select an option --</option>
                                                        @foreach($orgList as $value)
                                                            <option
                                                                value="{{$value->organization_id}}">{{$value->organization_name}}</option>
                                                        @endforeach
                                                    </select>--}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Person Name</label>
{{--                                                    <input type="text" id="def_name" name="def_name"--}}
{{--                                                           class="form-control "--}}
{{--                                                           placeholder="Person Name" value="">--}}
                                                    <div id="def_name_div">
                                                        <select class="custom-select select2" name="def_name"
                                                                id="def_name">
                                                        </select>
                                                    </div>
                                                    <div id="def_input_div">
                                                        <input type="text" id="def_name_input" name="def_name_input"
                                                               class="form-control "
                                                               placeholder="Person Name" value="" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Designation</label>
                                                    <input type="text" id="def_desig" name="def_desig"
                                                           class="form-control "
                                                           placeholder="Designation" value="">
                                                    <input type="hidden" id="def_desig_id" name="def_desig_id">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Address</label>
                                                    <input type="text" id="def_address" name="def_address"
                                                           class="form-control "
                                                           placeholder="Address" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Contact No.</label>
                                                    <input type="text" id="def_contact_no" name="def_contact_no"
                                                           class="form-control "
                                                           placeholder="Contact No." value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1" align="right">
                                                <div id="start-no-field"
                                                     class="form-group">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary add-row-def">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mt-2">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" id="table-def">
                                                        <thead>
                                                        <tr>
                                                            <th style="height: 25px;text-align: left; width: 5%">#</th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Organization Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Person Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 15%">
                                                                Designation
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Address
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 20%">
                                                                Contact No
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody id="def_body">
                                                        @if(!empty($defendentData))
                                                            @foreach($defendentData as $key=>$Data)
                                                                <tr>
                                                                    <td>
                                                                        <input type='checkbox' name='record'>
                                                                        <input type='hidden' name='def_org_name[]'
                                                                               value='{{$Data->organization_name}}'>
                                                                        <input type='hidden' name='def_name[]'
                                                                               value='{{$Data->party_details_name}}'>
                                                                        <input type='hidden' name='def_desig[]'
                                                                               value='{{$Data->designation}}'>
                                                                        <input type='hidden' name='def_desig_id[]'
                                                                               value='{{$Data->designation_id}}'>
                                                                        <input type='hidden' name='def_org_id[]'
                                                                               value='{{$Data->organization_id}}'>
                                                                        <input type='hidden' name='def_address[]'
                                                                               value='{{$Data->party_details_address}}'>
                                                                        <input type='hidden' name='def_contact_no[]'
                                                                               value='{{$Data->party_contact_no}}'>
                                                                        <input type='hidden' name='def_emp_code[]'
                                                                               value='{{$Data->party_emp_id}}'>
                                                                        <input type='hidden' name='def_chk_complain_from[]'
                                                                               value='{{$Data->org_from}}'>
                                                                        <input type='hidden'
                                                                               name='def_party_details_id[]'
                                                                               value='{{($Data)?$Data->party_details_id:'0'}}'
                                                                               class="def_party_details_id">
                                                                    </td>
                                                                    <td>{{$Data->organization_name}}</td>
                                                                    <td>{{$Data->party_details_name}}</td>
                                                                    <td>{{$Data->designation}}</td>
                                                                    <td>{{$Data->party_details_address}}</td>
                                                                    <td>{{$Data->party_contact_no}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @if(!empty(old('def_org_name')))
                                                                @php $i=1; @endphp
                                                                @foreach(old('def_org_name') as $key=>$Data)
                                                                    <tr>
                                                                        <td>
                                                                            <input type='checkbox' name='record'>
                                                                            <input type='hidden' name='def_org_name[]'
                                                                                   value='{{isset($Data) ? $Data : ''}}'>
                                                                            <input type='hidden' name='def_name[]'
                                                                                   value='{{isset(old('def_name')[$key]) ? old('def_name')[$key] : ''}}'>
                                                                            <input type='hidden' name='def_desig[]'
                                                                                   value='{{isset(old('def_desig')[$key]) ? old('def_desig')[$key] : ''}}'>
                                                                            <input type='hidden' name='def_desig_id[]'
                                                                                   value='{{isset(old('def_desig_id')[$key]) ? old('def_desig_id')[$key] : ''}}'>
                                                                            <input type='hidden' name='def_org_id[]'
                                                                                   value='{{isset(old('def_org_id')[$key]) ? old('def_org_id')[$key] : ''}}'>
                                                                            <input type='hidden' name='def_address[]'
                                                                                   value='{{isset(old('def_address')[$key]) ? old('def_address')[$key] : ''}}'>
                                                                            <input type='hidden' name='def_contact_no[]'
                                                                                   value='{{isset(old('def_contact_no')[$key]) ? old('def_contact_no')[$key] : ''}}'>
                                                                        </td>
                                                                        <td>{{isset($Data) ? $Data : ''}}</td>
                                                                        <td>{{isset(old('def_name')[$key]) ? old('def_name')[$key] : ''}}</td>
                                                                        <td>{{isset(old('def_desig')[$key]) ? old('def_desig')[$key] : ''}}</td>
                                                                        <td>{{isset(old('def_address')[$key]) ? old('def_address')[$key] : ''}}</td>
                                                                        <td>{{isset(old('def_contact_no')[$key]) ? old('def_contact_no')[$key] : ''}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    <button type="button"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-def">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

{{--                                <div class="row">--}}
{{--                                    <fieldset class="border p-2 col-sm-12">--}}
{{--                                        <legend class="w-auto required" style="font-size: 18px;">Pro Former Defender--}}
{{--                                        </legend>--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <input type="checkbox" id="vehicle1" name="defender" value="cpa">--}}
{{--                                                    <label>Chittagong Port Authority</label><br>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </fieldset>--}}
{{--                                </div>--}}

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
                                                           placeholder="Case Document Name" value="" autocomplete="off">
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
                                                            <th style="height: 25px;text-align: left; width: 50%">
                                                                Document Name
                                                            </th>
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
                                                                    <td><input type="text" class="form-control"
                                                                               name="doc_description[]"
                                                                               value="{{$value->doc_description}}"></td>
                                                                    <td>@if(isset($value->case_doc))
                                                                            <a href="{{ route('cms.case-info.case-info-file-download', [$value->case_doc_id]) }}"
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

                                <hr>
                            </div>
                        </div>


                        @if($editData)
                            <div class="row">
                                <div class="col-md-12 text-right" id="cancel">
                                    <button type="submit" id="update"
                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                        Update
                                    </button>
                                    <a href="{{url('/case-info')}}">
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
                                    <button type="submit" id="add" class="btn btn btn-dark shadow mb-1 btn-secondary"
                                            onclick="chkTable()">
                                        Save
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
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Case Information</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable mdl-data-table dataTable" id="case_info">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CASE NO</th>
                                    <th>CATEGORY</th>
                                    <th>COURT</th>
                                    <th>COMPLAINANT</th>
                                    <th>DEFENDENT</th>
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

        </div>
    </div>

    <div id="cover-spin"></div>

    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Organization Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form role="form-horizontal" action="" id="add_org_form">
                        @include('cms.partials.orgRegistation')
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script type="text/javascript">

        $("#add_org_form").submit(function(event) {
            event.preventDefault();
            var org_type = $('#org_type').val();
            var org_name = $('#org_name').val();
            var org_bangla = $('#org_bangla').val();
            var email = $('#email').val();
            var fax = $('#fax').val();
            var contact_no = $('#contact_no').val();
            var address = $('#address').val();
            var active = $('#active').val();
            var outsider = $('#outsider').val();
            $.ajax({
                type: "GET",
                data: {
                    org_type: org_type,
                    org_name: org_name,
                    org_bangla: org_bangla,
                    email: email,
                    fax: fax,
                    contact_no: contact_no,
                    address: address,
                    active: active,
                    outsider: outsider,
                },
                url: '/ajax-organization-registration',
                success: function (data) {
                    alert(data['o_status_message']);
                    $('#exampleModal').modal('hide');
                    $("#add_org_form").trigger('reset');
                },
                error: function (data) {
                    alert('error');
                }
            });
        });

        $("#add_org, #add_org_def").click(function () {
            var myModal = $('#exampleModal');
            myModal.modal({show: true});
            return false;
        });

        $("#reset").click(function () {
            $("#case_info_form").trigger('reset');
            $("#add_org_form").trigger('reset');
            // $('#org_type').val('').trigger('unselect');
            $('.select2').val('').trigger('change');
        });

        function getOrganizationDef() {
            $('#def_org_name').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",
                ajax: {
                    url: '/get-organization',
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
                            obj.id = obj.organization_id;
                            obj.text = obj.organization_name;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    }
                }
            });

            $('#def_name').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",

            });

            $('#def_org_name').on('select2:select', function (e) {
                var selectedOrganization = e.params.data;
                if (selectedOrganization.organization_id) {
                    $.ajax({
                        type: "GET",
                        url: '/get-organization-data/' + selectedOrganization.organization_id,
                        success: function (data) {
                            // $("#def_name").prop("disabled", true);
                            $("#def_address").prop("disabled", true);
                            $("#def_contact_no").prop("disabled", true);
                            $("#def_desig").prop("disabled", true);
                            // $('#def_name').val(data[0].authorize_emp_name);
                            // $('#def_address').val(data[0].address);
                            // $('#def_contact_no').val(data[0].contact_no);
                            // $('#def_desig').val(data[0].authorize_emp_designation);

                            $('select[name="def_name"]').empty();
                            $('select[name="def_name"]').append('<option value="">'+ 'Select' +'</option>');
                            $.each(data, function(key, value) {
                                $('select[name="def_name"]').append('<option value="'+ value.employee_name +'">'+ value.employee_name +'</option>');
                            });
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('select[name="def_name"]').on('change', function() {
                var emp_name = $(this).val();

                if (emp_name) {
                    $.ajax({
                        type: "GET",
                        url: '/get-emp-info',
                        data: {emp_name: emp_name},
                        success: function (data) {
                            // alert(data);
                            // $("#comp_name").prop("disabled", true);
                            // $("#comp_address").prop("disabled", true);
                            // $("#comp_contact_no").prop("disabled", true);
                            // $("#comp_desig").prop("disabled", true);
                            // $('#comp_name').val(data[0].employee_name);
                            $('#def_address').val(data[0].address);
                            $('#def_contact_no').val(data[0].contact_no);
                            $('#def_desig').val(data[0].employee_designation);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('#def_org_name').on('select2:clear', function (e) {
                $('#def_name').val('');
                $('#def_address').val('');
                $('#def_contact_no').val('');
                $('#def_desig').val('');
            });
        }

        function getOrganization() {
            $('#comp_org_name').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",
                ajax: {
                    url: '/get-organization',
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
                            obj.id = obj.organization_id;
                            obj.text = obj.organization_name;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    }
                }
            });

            $('#comp_name').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",

            });

            $('#comp_org_name').on('select2:select', function (e) {
                var selectedOrganization = e.params.data;
                //var emp_id = selectedEmployee.emp_id;
                if (selectedOrganization.organization_id) {
                    $.ajax({
                        type: "GET",
                        url: '/get-organization-data/' + selectedOrganization.organization_id,
                        success: function (data) {
                            // alert(data);
                            // $("#comp_name").prop("disabled", true);
                            $("#comp_address").prop("disabled", true);
                            $("#comp_contact_no").prop("disabled", true);
                            $("#comp_desig").prop("disabled", true);
                            // $('#comp_name').val(data[0].employee_name);
                            // $('#comp_address').val(data[0].address);
                            // $('#comp_contact_no').val(data[0].contact_no);
                            // $('#comp_desig').val(data[0].authorize_emp_designation);

                            $('select[name="comp_name"]').empty();
                            $('select[name="comp_name"]').append('<option value="">'+ 'Select' +'</option>');
                            $.each(data, function(key, value) {
                                $('select[name="comp_name"]').append('<option value="'+ value.employee_name +'">'+ value.employee_name +'</option>');
                            });
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('select[name="comp_name"]').on('change', function() {
                var emp_name = $(this).val();

                if (emp_name) {
                    $.ajax({
                        type: "GET",
                        url: '/get-emp-info',
                        data: {emp_name: emp_name},
                        success: function (data) {
                            // alert(data);
                            // $("#comp_name").prop("disabled", true);
                            // $("#comp_address").prop("disabled", true);
                            // $("#comp_contact_no").prop("disabled", true);
                            // $("#comp_desig").prop("disabled", true);
                            // $('#comp_name').val(data[0].employee_name);
                            $('#comp_address').val(data[0].address);
                            $('#comp_contact_no').val(data[0].contact_no);
                            $('#comp_desig').val(data[0].employee_designation);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('#comp_org_name').on('select2:clear', function (e) {
                $('#comp_name').val('');
                $('#comp_address').val('');
                $('#comp_contact_no').val('');
                $('#comp_desig').val('');
            });
        }

        $("#comp_chk_org").change(function () {
            let comp_chk_org = ($('#comp_chk_org').val());
            let comp_chk_complain_from = ($('#comp_chk_complain_from').val());
            if (comp_chk_org) {
                $('#complain_from_div').show();
                if (comp_chk_complain_from == 'O') {
                    if (comp_chk_org == 'Y') {
                        $("#comp_org_name").prop("disabled", false);
                        $("#comp_name").prop("disabled", false);
                        $("#comp_address").prop("disabled", true);
                        $("#comp_contact_no").prop("disabled", true);
                        $("#comp_desig").prop("disabled", true);

                        $('#comp_name_div').css('display', 'block');
                        $('#comp_input_div').css('display', 'none');

                        //Add Organization Button
                        $('#add_org').css('display', 'inline-block');
                    } else {
                        $("#comp_org_name").prop("disabled", true);
                        $("#comp_org_name").empty();
                        $("#comp_org_name").val("");
                        $("#comp_name").prop("disabled", false);
                        $("#comp_name_input").prop("disabled", false);
                        $("#comp_address").prop("disabled", false);
                        $("#comp_contact_no").prop("disabled", false);
                        $("#comp_desig").prop("disabled", false);

                        $('#comp_name_div').css('display', 'none');
                        $('#comp_input_div').css('display', 'block');

                        //Remove Organization
                        $('#add_org').css('display', 'none');
                    }
                    $('#comp_org_name').val('');
                    $('#comp_name').val('');
                    $('#comp_name_input').val('');
                    $('#comp_desig').val('');
                    $('#comp_desig_id').val('');
                    $('#comp_address').val('');
                    $('#comp_contact_no').val('');
                }
            } else {
                $('#complain_from_div').hide();
                $('#ifYes').hide();
                $('#with_org_comp_data').hide();

                //Remove Organization
                $('#add_org').css('display', 'none');
            }
        });

        $("#comp_chk_complain_from").change(function () {
            let comp_chk_complain_from = ($('#comp_chk_complain_from').val());
            let comp_chk_org = ($('#comp_chk_org').val());
            if (comp_chk_complain_from == 'C') {
                $('#ifYes').show();
                $('#with_org_comp_data').show();
                $("#comp_org_name").empty();
                $('#comp_name').val('');
                $('#comp_desig').val('');
                $('#comp_desig_id').val('');
                $('#comp_address').val('');
                $('#comp_contact_no').val('');
                $("#comp_org_name").prop("disabled", true);
                $("#comp_name").prop("disabled", true);
                $("#comp_desig").prop("disabled", true);
                $("#comp_address").prop("disabled", true);
                $("#comp_contact_no").prop("disabled", true);

                $('#comp_name_div').css('display', 'none');
                $('#comp_input_div').css('display', 'block');
                $("#comp_name_input").prop("disabled", true);

                //Remove Organization
                $('#add_org').css('display', 'none');
            } else if (comp_chk_complain_from == 'O') {
                $('#ifYes').hide();
                $('#with_org_comp_data').show();
                if (comp_chk_org == 'Y') {
                    $("#comp_org_name").prop("disabled", false);
                    $("#comp_name").prop("disabled", false);
                    $("#comp_desig").prop("disabled", true);
                    $("#comp_address").prop("disabled", true);
                    $("#comp_contact_no").prop("disabled", true);

                    $('#comp_name_div').css('display', 'block');
                    $('#comp_input_div').css('display', 'none');

                    //Add Organization Button
                    $('#add_org').css('display', 'inline-block');
                } else {
                    $("#comp_org_name").prop("disabled", true);
                    $("#comp_address").prop("disabled", false);
                    $("#comp_contact_no").prop("disabled", false);
                    $("#comp_desig").prop("disabled", false);

                    $('#comp_name_div').css('display', 'none');
                    $('#comp_input_div').css('display', 'block');
                    $("#comp_name_input").val('');
                    $("#comp_name_input").prop("disabled", false);

                    //Remove Organization
                    $('#add_org').css('display', 'none');
                }
                // $("#comp_name").prop("disabled", false);
                // $("#comp_desig").prop("disabled", false);
                // $("#comp_address").prop("disabled", false);
                // $("#comp_contact_no").prop("disabled", false);
                $('#comp_org_name').val('');
                $('#comp_name').val('');
                $('#comp_desig').val('');
                $('#comp_desig_id').val('');
                $('#comp_address').val('');
                $('#comp_contact_no').val('');

            } else {
                $('#ifYes').hide();
                $('#with_org_comp_data').hide();
            }
        });

        $("#def_chk_org").change(function () {
            let def_chk_org = ($('#def_chk_org').val());
            let comp_chk_complain_from = ($('#def_chk_complain_from').val());
            if (def_chk_org) {
                $('#def_complain_from_div').show();
                if (comp_chk_complain_from == 'O') {
                    if (def_chk_org == 'Y') {
                        $("#def_org_name").prop("disabled", false);
                        $("#def_name").prop("disabled", false);
                        $("#def_address").prop("disabled", true);
                        $("#def_contact_no").prop("disabled", true);
                        $("#def_desig").prop("disabled", true);

                        $('#def_name_div').css('display', 'block');
                        $('#def_input_div').css('display', 'none');

                        //Add Organization Button
                        $('#add_org_def').css('display', 'inline-block');
                    } else {
                        $("#def_org_name").prop("disabled", true);
                        $("#def_org_name").empty();
                        $("#def_org_name").val("");
                        $("#def_name").prop("disabled", false);
                        $("#def_name_input").prop("disabled", false);
                        $("#def_address").prop("disabled", false);
                        $("#def_contact_no").prop("disabled", false);
                        $("#def_desig").prop("disabled", false);

                        $('#def_name_div').css('display', 'none');
                        $('#def_input_div').css('display', 'block');

                        //Remove Organization
                        $('#add_org_def').css('display', 'none');
                    }
                    $('#def_org_name').val('');
                    $('#def_name').val('');
                    $('#def_name_input').val('');
                    $('#def_desig').val('');
                    $('#def_desig_id').val('');
                    $('#def_address').val('');
                    $('#def_contact_no').val('');
                }
            } else {
                $('#def_complain_from_div').hide();
                $('#ifYes1').hide();
                $('#with_org_def_data').hide();

                //Remove Organization
                $('#add_org_def').css('display', 'none');
            }
        });

        $("#def_chk_complain_from").change(function () {
            let def_chk_complain_from = ($('#def_chk_complain_from').val());
            let def_chk_org = ($('#def_chk_org').val());
            if (def_chk_complain_from == 'C') {
                $('#ifYes1').show();
                $('#with_org_def_data').show();
                $("#def_org_name").empty();
                $('#def_name').val('');
                $('#def_desig').val('');
                $('#def_desig_id').val('');
                $('#def_address').val('');
                $('#def_contact_no').val('');
                $("#def_org_name").prop("disabled", true);
                $("#def_name").prop("disabled", true);
                $("#def_desig").prop("disabled", true);
                $("#def_address").prop("disabled", true);
                $("#def_contact_no").prop("disabled", true);

                $('#def_name_div').css('display', 'none');
                $('#def_input_div').css('display', 'block');
                $("#def_name_input").prop("disabled", true);

                //Remove Organization
                $('#add_org_def').css('display', 'none');
            } else if (def_chk_complain_from == 'O') {
                $('#ifYes1').hide();
                $('#with_org_def_data').show();
                if (def_chk_org == 'Y') {
                    $("#def_org_name").prop("disabled", false);
                    $("#def_name").prop("disabled", false);
                    $("#def_desig").prop("disabled", true);
                    $("#def_address").prop("disabled", true);
                    $("#def_contact_no").prop("disabled", true);

                    $('#def_name_div').css('display', 'block');
                    $('#def_input_div').css('display', 'none');

                    //Add Organization Button
                    $('#add_org_def').css('display', 'inline-block');
                } else {
                    $("#def_org_name").prop("disabled", true);
                    $("#def_address").prop("disabled", false);
                    $("#def_contact_no").prop("disabled", false);
                    $("#def_desig").prop("disabled", false);

                    $('#def_name_div').css('display', 'none');
                    $('#def_input_div').css('display', 'block');
                    $("#def_name_input").val('');
                    $("#def_name_input").prop("disabled", false);

                    //Remove Organization
                    $('#add_org_def').css('display', 'none');
                }
                // $("#def_name").prop("disabled", false);
                // $("#def_desig").prop("disabled", false);
                // $("#def_address").prop("disabled", false);
                // $("#def_contact_no").prop("disabled", false);
                $('#def_org_name').val('');
                $('#def_name').val('');
                $('#def_desig').val('');
                $('#def_desig_id').val('');
                $('#def_address').val('');
                $('#def_contact_no').val('');
            } else {
                $('#ifYes1').hide();
                $('#with_org_def_data').hide();
            }
        });

        $("#case_status").change(function () {
            let case_status = ($('#case_status').val());
            if (case_status == '5') {
                $('#prev_case').show();
            } else {
                $('#prev_case').hide();
                $('#prev_case_no').val('');

            }
        });

        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Complainant Data Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#def_body tr').length == 0) {
                Swal.fire({
                    title: 'Defendant Data Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#file_body tr').length == 0) {
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

        function encodeFileAsURL() {
            var file = document.querySelector('input[type=file]')['files'][0];
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                $("#converted_file").val(baseString);
                //console.log(baseString);
            };
            reader.readAsDataURL(file);
        }

        $(document).ready(function () {
            selectCpaEmployee();
            selectCpaEmployee2();
            caseInfolist();
            getCaseData();
            getOrganization();
            getOrganizationDef();
        });

        function getCaseData() {
            $('#prev_case_no').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",
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

        $('#caseDateDP').datetimepicker({

            format: 'DD-MM-YYYY',
            //maxDate: new Date(),
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

        function caseInfolist() {
            $('#case_info').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/case-info-dataTable',
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
                    {"data": 'complainant', "name": 'complainant'},
                    {"data": 'defendant', "name": 'defendant'},
                    {"data": 'case_status_name', "name": 'case_status_name'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }

        function selectCpaEmployee() {
            $('#cpa_reporter_emp_id').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",
                ajax: {
                    url: '/get-emp-data',
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
                            obj.text = obj.emp_code + ' - ' + obj.emp_name;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    }
                }
            });

            $('#cpa_reporter_emp_id').on('select2:select', function (e) {
                var selectedEmployee = e.params.data;
                //var emp_id = selectedEmployee.emp_id;

                if (selectedEmployee.emp_code) {
                    let comp_chk_org = ($('#comp_chk_org').val());
                    $.ajax({
                        type: "GET",
                        url: '/get-emp-data/' + selectedEmployee.emp_id,
                        success: function (data) {
                            // document.getElementsByName('comp_name').placeholder = data.emp_emergency_contact_name;
                            // document.getElementById('comp_name')[0].value = data.emp_emergency_contact_name;
                            // $("#comp_name").prop("disabled", false);
                            $('#comp_name_input').val(data.emp_emergency_contact_name);
                            $('#comp_address').val(data.emp_emergency_contact_address);
                            $('#comp_contact_no').val(data.emp_emergency_contact_mobile);
                            $('#comp_desig').val(data.designation);
                            $('#comp_desig_id').val(data.designation_id);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('#cpa_reporter_emp_id').on('select2:clear', function (e) {
                $('#cpa_reporter_name').val('');
                $('#cpa_reporter_designation').val('');
                $('#cpa_reporter_department').val('');
                $('#cpa_reporter_section').val('');
            });
        }

        function selectCpaEmployee2() {
            $('#cpa_reporter_emp_id1').select2({
                placeholder: "Select",
                allowClear: true,
                width: "100%",
                ajax: {
                    url: '/get-emp-data',
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
                            obj.text = obj.emp_code + ' - ' + obj.emp_name;
                            return obj;
                        });
                        return {
                            results: formattedResults,
                        };
                    }
                }
            });

            $('#cpa_reporter_emp_id1').on('select2:select', function (e) {
                var selectedEmployee = e.params.data;
                //var emp_id = selectedEmployee.emp_id;

                if (selectedEmployee.emp_code) {
                    $.ajax({
                        type: "GET",
                        url: '/get-emp-data/' + selectedEmployee.emp_id,
                        success: function (data) {
                            // $('#def_name').val(data.emp_emergency_contact_name);
                            $('#def_name_input').val(data.emp_emergency_contact_name);
                            $('#def_address').val(data.emp_emergency_contact_address);
                            $('#def_contact_no').val(data.emp_emergency_contact_mobile);
                            $('#def_desig').val(data.designation);
                            $('#def_desig_id').val(data.designation_id);
                        },
                        error: function (data) {
                            alert('error');
                        }
                    });
                }
            });

            $('#cpa_reporter_emp_id').on('select2:clear', function (e) {
                $('#cpa_reporter_name').val('');
                $('#cpa_reporter_designation').val('');
                $('#cpa_reporter_department').val('');
                $('#cpa_reporter_section').val('');
            });
        }
    </script>

    <script>
        $(document).ready(function () {

            $(document).on('change', '.custom-file-input', function () {
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(fileName);
            })

            $(".add-row-comp").click(function () {
                let comp_org_name = $("#comp_chk_org option:selected").val();
                let comp_chk_complain_from = $("#comp_chk_complain_from option:selected").val();
                let comp_org_id = $("#comp_org_name option:selected").val();
                let emp_code = $("#cpa_reporter_emp_id option:selected").val();
                if(!comp_org_id){
                    comp_org_id = '';
                }

                let comp_name = $("#comp_name_input").val();
                let comp_desig = $("#comp_desig").val();
                let comp_desig_id = $("#comp_desig_id").val();
                let comp_address = $("#comp_address").val();
                let comp_contact_no = $("#comp_contact_no").val();

                if (comp_org_name == 'Y' && comp_chk_complain_from == 'C') {
                    comp_org_name = 'CPA';
                    //for CPA

                    if(!emp_code)
                    {
                        comp_name = 'CPA';
                    }
                } else if (comp_org_name == 'N' && comp_chk_complain_from == 'C') {
                    comp_org_name = '';
                } else if (comp_org_name == 'N' && comp_chk_complain_from == 'O') {
                    comp_org_name = '';
                } else if (comp_org_name == 'Y' && comp_chk_complain_from == 'O') {
                    let comp_org = $("#comp_org_name option:selected").text();
                    comp_org_name = comp_org;
                    comp_name = $("#comp_name").val();

                    if(comp_org_name && !comp_name)
                    {
                        comp_name = comp_org_name;
                        let comp_org_id = $("#comp_org_name option:selected").val();
                        $.ajax({
                            async: false,
                            type: "GET",
                            url: '/get-org-name-ajax',
                            data: { id : comp_org_id },
                            success: function (data) {
                                comp_address = data[0].address;
                                comp_contact_no = data[0].contact_no;
                            },
                            error: function (data) {
                                alert('error');
                            }
                        });
                    }
                }

                if(comp_name) {

                    var count;
                    var complainent_count = parseInt(document.getElementById("complainent_count").value);
                    if (complainent_count > 0) {
                        count = complainent_count + 1;
                    } else {
                        var comp_count = parseInt(document.getElementById("comp_count").value);
                        count = comp_count + 1;
                    }

                    var markup = "<tr><td><input type='checkbox' name='record'>" +
                        "<input type='hidden' name='comp_org_name[]' value='" + comp_org_name + "'>" +
                        "<input type='hidden' name='comp_name[]' value='" + comp_name + "'>" +
                        "<input type='hidden' name='comp_desig[]' value='" + comp_desig + "'>" +
                        "<input type='hidden' name='comp_desig_id[]' value='" + comp_desig_id + "'>" +
                        "<input type='hidden' name='comp_org_id[]' value='" + comp_org_id + "'>" +
                        "<input type='hidden' name='comp_address[]' value='" + comp_address + "'>" +
                        "<input type='hidden' name='comp_contact_no[]' value='" + comp_contact_no + "'>" +
                        "<input type='hidden' name='comp_chk_complain_from[]' value='" + comp_chk_complain_from + "'>" +
                        "<input type='hidden' name='comp_emp_code[]' value='" + emp_code + "'>" +
                        "</td><td>" + comp_org_name + "</td><td>" + comp_name + "</td><td>" + comp_desig + "</td><td>" + comp_address + "</td><td>" + comp_contact_no + "</td></tr>";
                    $("#comp_contact_no").val("");
                    $("#comp_address").val("");
                    $("#comp_name_input").val("");
                    $("#comp_desig_id").val("");
                    $("#comp_desig").val("");

                    $("#comp_name").empty();
                    $("#comp_org_name").empty();
                    //$("#comp_chk_org").val("");
                    //$("#comp_chk_complain_from").val("");
                    //$('#complain_from_div').hide();
                    //$('#ifYes').hide();
                    $('#cpa_reporter_emp_id').empty();
                    //$('#with_org_comp_data').hide();
                    $("#table-comp tbody").append(markup);
                    $("#comp_count").val(count);
                }
            });

            $(".delete-row-comp").click(function () {
                $("#table-comp tbody").find('input[name="record"]').each(function () {
                    if ($(this).is(":checked")) {
                        let party_details_id = $(this).closest('tr').find('.comp_party_details_id').val();
                        //alert(party_details_id)
                        if (party_details_id) {
                            //alert(party_details_id)
                            $.ajax({
                                type: 'GET',
                                url: '/party-data-remove',
                                data: {party_details_id: party_details_id},
                                success: function (msg) {
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $(this).parents("tr").remove();
                                        location.reload();
                                    });
                                }
                            });
                        } else {
                            $(this).parents("tr").remove();
                        }

                    }
                });
            });

            $(".add-row-def").click(function () {

                let def_org_name = $("#def_chk_org option:selected").val();
                let def_chk_complain_from = $("#def_chk_complain_from option:selected").val();
                let def_org_id = $("#def_org_name option:selected").val();
                let emp_code = $("#cpa_reporter_emp_id1 option:selected").val();
                if(!def_org_id){
                    def_org_id = '';
                }

                var def_desig = $("#def_desig").val();
                var def_desig_id = $("#def_desig_id").val();
                var def_contact_no = $("#def_contact_no").val();
                var def_address = $("#def_address").val();
                var def_name = $("#def_name_input").val();

                if (def_org_name == 'Y' && def_chk_complain_from == 'C') {
                    def_org_name = 'CPA';
                    //for CPA
                    if(!emp_code)
                    {
                        def_name = 'CPA';
                    }
                } else if (def_org_name == 'N' && def_chk_complain_from == 'C') {
                    def_org_name = '';
                } else if (def_org_name == 'N' && def_chk_complain_from == 'O') {
                    def_org_name = '';
                } else if (def_org_name == 'Y' && def_chk_complain_from == 'O') {
                    let def_org = $("#def_org_name option:selected").text();
                    def_org_name = def_org;
                    def_name = $("#def_name").val();

                    if(def_org_name && !def_name)
                    {
                        def_name = def_org_name;
                        let def_org_id = $("#def_org_name option:selected").val();
                        $.ajax({
                            async: false,
                            type: "GET",
                            url: '/get-org-name-ajax',
                            data: { id : def_org_id },
                            success: function (data) {
                                def_address = data[0].address;
                                def_contact_no = data[0].contact_no;
                            },
                            error: function (data) {
                                alert('error');
                            }
                        });
                    }
                }

                if(def_name) {
                    var count;
                    var defndent_count = parseInt(document.getElementById("defndent_count").value);

                    if (defndent_count > 0) {
                        count = defndent_count + 1;
                    } else {
                        var comp_count = parseInt(document.getElementById("def_count").value);
                        count = comp_count + 1;
                    }

                    var markup = "<tr><td><input type='checkbox' name='record'>" +
                        "<input type='hidden' name='def_org_name[]' value='" + def_org_name + "'>" +
                        "<input type='hidden' name='def_name[]' value='" + def_name + "'>" +
                        "<input type='hidden' name='def_desig[]' value='" + def_desig + "'>" +
                        "<input type='hidden' name='def_desig_id[]' value='" + def_desig_id + "'>" +
                        "<input type='hidden' name='def_org_id[]' value='" + def_org_id + "'>" +
                        "<input type='hidden' name='def_address[]' value='" + def_address + "'>" +
                        "<input type='hidden' name='def_contact_no[]' value='" + def_contact_no + "'>" +
                        "<input type='hidden' name='def_chk_complain_from[]' value='" + def_chk_complain_from + "'>" +
                        "<input type='hidden' name='def_emp_code[]' value='" + emp_code + "'>" +
                        "</td><td>" + def_org_name + "</td><td>" + def_name + "</td><td>" + def_desig + "</td><td>" + def_address + "</td><td>" + def_contact_no + "</td></tr>";
                    $("#def_contact_no").val("");
                    $("#def_address").val("");
                    $("#def_name_input").val("");
                    $("#def_desig_id").val("");
                    $("#def_desig").val("");

                    $("#def_name").empty();
                    $("#def_org_name").empty();
                    //$("#def_chk_org").val("");
                    //$("#def_chk_complain_from").val("");
                    //$('#def_complain_from_div').hide();
                    //$('#ifYes1').hide();
                    $('#cpa_reporter_emp_id1').empty();
                    //$('#with_org_def_data').hide();
                    $("#table-def tbody").append(markup);
                    $("#def_count").val(count);
                }
            });

            $(".delete-row-def").click(function () {
                $("#table-def tbody").find('input[name="record"]').each(function () {
                    if ($(this).is(":checked")) {
                        let party_details_id = $(this).closest('tr').find('.def_party_details_id').val();
                        if (party_details_id) {
                            $.ajax({
                                type: 'GET',
                                url: '/party-data-remove',
                                data: {party_details_id: party_details_id},
                                success: function (msg) {
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $(this).parents("tr").remove();
                                        location.reload();
                                    });
                                }
                            });
                        } else {
                            $(this).parents("tr").remove();
                        }

                    }
                });
            });

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
                    "</td><td>" + case_doc_name + "</td><td>" + fileName + "</td></tr>";
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
                                url: '/case-doc-remove',
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
    </script>
@endsection


