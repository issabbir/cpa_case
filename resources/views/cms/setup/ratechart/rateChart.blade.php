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
                    <h4 class="card-title">Rate Chart</h4><!---->
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
                    <form role="form-horizontal"
                          action="{{($editData)?url('/rate-chart/edit/'.$editData['rate_chart_id']):url('/rate-chart')}}"
                          method="post" id="rate_chart_form">

                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Service Name</label>
                                            <select required class="custom-select select2" name="service_name"
                                                    id="service_name">
                                                <option value="">-- Please select an option --</option>
                                                @foreach($serviceNameList as $service)
                                                    <option value="{{$service->assignment_type_id}}"
                                                    @if(!empty($editData)) @if($service->assignment_type_id == $editData['assignment_type_id']) {{'selected="selected"'}} @endif @endif
                                                    >{{$service->assignment_type_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Rate From</label>
                                            <input required type="number" id="rate_from" name="rate_from" style="text-align:right;"
                                                   class="form-control"
                                                   value="{{old('rate_from',($editData)?$editData['minimum_rate']:'')}}"
                                                   autocomplete="off">
                                            @if(!empty($editData))
                                                <input type="hidden" id="rate_chart_id" name="rate_chart_id"
                                                       class="form-control"
                                                       value="{{($editData)?$editData['rate_chart_id']:''}}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Rate To</label>
                                            <input required type="number" id="rate_to" name="rate_to" style="text-align:right;"
                                                   class="form-control"
                                                   value="{{old('rate_to',($editData)?$editData['maximum_rate']:'')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Active From</label>
                                            <div class="input-group date" id="activeFromDP" data-target-input="nearest">
                                                <input required type="text"
                                                       value="{{old('active_from',isset($editData['active_from']) ? date('d-m-Y', strtotime($editData['active_from'])) :'')}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#activeFromDP"
                                                       id="active_from"
                                                       name="active_from"
                                                       placeholder="Active From"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Active To</label>
                                            <div class="input-group date" id="activeToDP" data-target-input="nearest">
                                                <input required type="text"
                                                       value="{{old('active_from',isset($editData['active_to']) ? date('d-m-Y', strtotime($editData['active_to'])) :'')}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#activeToDP"
                                                       id="active_to"
                                                       name="active_to"
                                                       placeholder="Active To"
                                                       autocomplete="off"
                                                />
                                            </div>
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
                                            <label for="name">Description</label>
                                            <textarea placeholder="Enter Description"
                                                      rows="4" wrap="soft"
                                                      name="description"
                                                      class="form-control"
                                                      id="instructions">{{old('description',($editData)?$editData['rate_description']:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($editData)
                            <div class="row">
                                <div class="col-md-12 text-right" id="cancel">
                                    <button type="submit" id="update"
                                            class="btn btn btn-dark shadow mb-1 btn-secondary">
                                        Update
                                    </button>
                                    <a href="{{url('/rate-chart')}}">
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
            @include('cms.setup.ratechart.rateChartList')

        </div>
    </div>
@endsection
