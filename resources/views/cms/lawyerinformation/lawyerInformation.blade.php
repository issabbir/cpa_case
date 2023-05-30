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
                    <h4 class="card-title">Lawyer Information</h4>
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
                    <form role="form-horizontal" enctype="multipart/form-data"
                          action="{{($editData)?url('/lawyer-info/edit/'.$editData['lawyer_id']):url('/lawyer-info')}}"
                          id="lawyer_info_form" method="post">

                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Lawyer Name</label>
                                            <input required type="text" id="lawyer_name" name="lawyer_name"
                                                   class="form-control" placeholder="Lawyer Name"
                                                   value="{{old('lawyer_name',($editData)?$editData['lawyer_name']:'')}}"
                                                   autocomplete="off">
                                            @if(!empty($editData))
                                                <input type="hidden" id="lawyer_id" name="lawyer_id"
                                                       class="form-control"
                                                       value="{{($editData)?$editData['lawyer_id']:''}}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lawyer Name (Bangla)</label>
                                            <input type="text" id="lawyer_name_bng" name="lawyer_name_bng"
                                                   class="form-control" placeholder="Lawyer Name (Bangla)"
                                                   value="{{old('lawyer_name_bng',($editData)?$editData['lawyer_name_bng']:'')}}"
                                                   autocomplete="off">

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Mobile No</label>
                                            <input type="number" id="mobile_no" name="mobile_no"
                                                   onKeyPress="if(this.value.length==11) return false;"
                                                   class="form-control" placeholder="Mobile No"
                                                   value="{{old('mobile_no',($editData)?$editData['contact_no']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Licence No</label>
                                            <input type="text" id="licence_no" name="licence_no"
                                                   class="form-control" placeholder="Licence No"
                                                   value="{{old('licence_no',($editData)?$editData['license_no']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Present Address</label>
                                            <input type="text" id="lawyer_present_address"
                                                   name="lawyer_present_address" class="form-control"
                                                   placeholder="Present Address"
                                                   value="{{old('lawyer_present_address',($editData)?$editData['present_address']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Division</label>
                                            <select class="custom-select select2" name="division"
                                                    id="division">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($divisionList as $division)
                                                    <option value="{{$division->geo_division_id}}"
                                                    @if(!empty($editData)) @if($division->geo_division_id == $editData['division_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$division->geo_division_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">District</label>
                                            <select class="custom-select select2" name="district"
                                                    id="district">
                                                <option value="">-- Please select an option --</option>
                                                @if(!empty($districtList))
                                                    @foreach($districtList as $district)
                                                        <option value="{{$district->geo_district_id}}"
                                                        @if(!empty($editData)) @if($district->geo_district_id == $editData['district_id']) {{'selected="selected"'}} @endif @endif
                                                        >{{$district->geo_district_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Thana</label>
                                            <select class="custom-select select2" name="thana" id="thana">
                                                <option value="">-- Please select an option --</option>
                                                @if(!empty($thanaList))
                                                    @foreach($thanaList as $thana)
                                                        <option value="{{$thana->geo_thana_id}}"
                                                        @if(!empty($editData)) @if($thana->geo_thana_id == $editData['thana_id']) {{'selected="selected"'}} @endif @endif
                                                        >{{$thana->geo_thana_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="">Permanent Address</label>
                                            <input type="text" id="lawyer_permanent_address"
                                                   name="lawyer_permanent_address" class="form-control"
                                                   placeholder="Permanent Address"
                                                   value="{{old('lawyer_permanent_address',($editData)?$editData['permanent_address']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="">Chamber Address</label>
                                            <input type="text" id="lawyer_chamber_address"
                                                   name="lawyer_chamber_address" class="form-control"
                                                   placeholder="Chamber Address"
                                                   value="{{old('lawyer_chamber_address',($editData)?$editData['chamber_address']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Enlistment Number</label>
                                            <input type="text" id="enlistment_no" name="enlistment_no"
                                                   class="form-control" placeholder="Enlistment Number"
                                                   value="{{old('enlistment_no',($editData)?$editData['enlistment_no']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Enlistment Date</label>
                                            <div class="input-group date" id="enlistment_dateDP" data-target-input="nearest">
                                                <input type="text"
                                                       value="{{old('enlistment_date',isset($editData['enlistment_date']) ? date('d-m-Y', strtotime($editData['enlistment_date'])) :'')}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#enlistment_dateDP"
                                                       id="enlistment_date"
                                                       name="enlistment_date"
                                                       placeholder="Enlistment Date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Expired On</label>
                                            <div class="input-group date" id="expired_onDP" data-target-input="nearest">
                                                <input type="text"
                                                       value="{{old('expired_on',isset($editData['expiry_date']) ? date('d-m-Y', strtotime($editData['expiry_date'])) :'')}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#expired_onDP"
                                                       id="expired_on"
                                                       name="expired_on"
                                                       placeholder="Expired On"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Bank Name</label>
                                            <select class="custom-select select2" name="bank" id="bank">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($bankList as $bank)
                                                    <option value="{{$bank->bank_id}}"
                                                    @if(!empty($editData)) @if($bank->bank_id == $editData['bank_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Branch Name</label>
                                            <select class="custom-select select2" name="branch" id="branch">
                                                <option value="">-- Please select an option --</option>
                                                @if(!empty($branchList))
                                                    @foreach($branchList as $branch)
                                                        <option value="{{$branch->branch_id}}"
                                                        @if(!empty($editData)) @if($branch->branch_id == $editData['branch_id']) {{'selected="selected"'}} @endif @endif
                                                        >{{$branch->branch_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Bank Acc. Name</label>
                                            <input type="text" id="bank_ac_name" name="bank_ac_name"
                                                   class="form-control" placeholder="Bank Acc. Name"
                                                   value="{{old('bank_ac_name',($editData)?$editData['bank_acc_name']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="">Bank Acc. No</label>
                                            <input type="text" id="bank_ac_no" name="bank_ac_no"
                                                   class="form-control" placeholder="Bank Acc. No"
                                                   value="{{old('bank_ac_no',($editData)?$editData['bank_acc_no']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">Status</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="active_yn"
                                                           required id="active_yn" value="Y" checked
                                                           @if(isset($editData['active_yn']) && $editData['active_yn'] == "Y")
                                                           checked
                                                        @endif
                                                    />
                                                    <label class="form-check-label"
                                                           for="reporter_cpa_yes">Active</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="active_yn"
                                                           required id="active_yn" value="N"
                                                           @if(isset($editData['active_yn']) && $editData['active_yn'] == "N")
                                                           checked
                                                        @endif
                                                    />
                                                    <label class="form-check-label"
                                                           for="reporter_cpa_no">In-Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Court</label>
                                            <select class="custom-select select2" multiple="multiple" name="chk_court[]"
                                                    id="chk_court[]">
                                                @foreach($courtList as $value)
                                                    <option value="{{$value->court_id}}"
                                                    @if(!empty($userCourtData))
                                                        @foreach($userCourtData as $prereq)
                                                            @if($prereq->court_id == $value->court_id && $prereq->active_yn == 'Y') {{'selected="selected"'}}
                                                                @endif
                                                            @endforeach
                                                        @endif >
                                                        {{$value->court_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @if($editData)
                            <div class="row">
                                <div class="col-md-12 text-right" id="cancel">
                                    <button type="submit" id="update"
                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">
                                        Update
                                    </button>
                                    <a href="{{url('/lawyer-info')}}">
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

            @include('cms.lawyerinformation.lawyerInformationList')

        </div>
    </div>
@endsection



