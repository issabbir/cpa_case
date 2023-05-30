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
                    <h4 class="card-title">Court Information</h4><!---->
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
                    @error('procedureError')<span class="error">{{ $message }}</span>@enderror
                    <form role="form-horizontal"
                          action="{{($editData)?url('/court-info/edit/'.$editData['court_id']):url('/court-info')}}"
                          id="court_info_form" method="post" onsubmit="return checkLotForm()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Court Name</label>
                                            <input required type="text" id="court_name" name="court_name"
                                                   class="form-control" placeholder="Court Name"
                                                   value="{{old('court_name',($editData)?$editData['court_name']:'')}}"
                                                   autocomplete="off">
                                            @if(!empty($editData))
                                                <input type="hidden" id="court_id" name="court_id" class="form-control"
                                                       value="{{($editData)?$editData['court_id']:''}}">
                                                <input type="hidden" id="court_address_bng" name="court_address_bng"
                                                       class="form-control"
                                                       value="{{($editData)?$editData['court_address_bng']:''}}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Court Name (Bangla)</label>
                                            <input type="text" id="court_name_bng" name="court_name_bng"
                                                   class="form-control" placeholder="Court Name (Bangla)"
                                                   value="{{old('court_name_bng',($editData)?$editData['court_name_bng']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="">Court Category</label>
                                        <select class="custom-select select2" name="court_category"
                                                id="court_category">
                                            <option value="">-- Please select an option --</option>
                                            @foreach($courtList as $court)
                                                <option value="{{$court->court_category_id}}"
                                                @if(!empty($editData)) @if($court->court_category_id == $editData['court_category_id']) {{'selected="selected"'}} @endif @endif
                                                >{{$court->court_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="">Court Address</label>
                                            <input type="text" id="court_address" name="court_address"
                                                   class="form-control" placeholder="Court Address"
                                                   value="{{old('court_address',($editData)?$editData['court_address']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Court Address (Bangla)</label>
                                            <input type="text" id="court_address_bng" name="court_address_bng"
                                                   class="form-control" placeholder="Court Address (Bangla)"
                                                   value="{{old('court_address_bng',($editData)?$editData['court_address_bng']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea placeholder="Enter Description"
                                                      rows="4" wrap="soft"
                                                      name="description"
                                                      class="form-control"
                                                      id="instructions">{{old('description',($editData)?$editData['description']:'')}}</textarea>
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
                                    <a href="{{url('/court-info')}}">
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
                        <script type="text/javascript"
                                src="{{asset('assets/plugins/switchery/js/switchery.min.js')}}"></script>
                    </form>

                </div>
                <!-- Table End -->

            </div>
            @include('cms.setup.courtinformation.courtInformationList')

        </div>
    </div>
@endsection




