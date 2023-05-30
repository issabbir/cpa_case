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
                    <h4 class="card-title">Case Detail & Lawyer Assignment</h4><!---->
                    <hr>
                    <form role="form-horizontal"
                          action="{{($caseData)?url('/lawyer-assignment/'.$caseData['case_id']):url('/lawyer-assignment')}}"
                          method="post" onsubmit="return checkLotForm()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('user_type') has-error @enderror">
                                            <label for="name">Case No</label>
                                            <input type="text" id="case_no" name="case_no" class="form-control"
                                                   value="{{($caseData)?$caseData['case_no']:''}}" autocomplete="off"
                                                   disabled>
                                            <input type="hidden" id="case_id" name="case_id" class="form-control"
                                                   value="{{($caseData)?$caseData['case_id']:''}}">
                                            <input type="hidden" id="case_description" name="case_description"
                                                   class="form-control"
                                                   value="{{($caseData)?$caseData['case_description']:''}}">
                                            <input type="hidden" id="case_status_id" name="case_status_id"
                                                   class="form-control"
                                                   value="{{($caseData)?$caseData['case_status_id']:''}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required">Lawyer</label>
                                        <select required class="custom-select select2" name="lawyer_id" id="lawyer_id">
                                            <option value="">-- Please select an option --</option>
                                            @foreach($lawyerList as $lawyer)
                                                <option value="{{$lawyer->pass_value}}">{{$lawyer->show_value}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group @error('user_type') has-error @enderror">
                                            <label for="name">Description</label>
                                            <textarea rows="4" wrap="soft"
                                                      name="cat_desc"
                                                      class="form-control"
                                                      id="instructions"
                                                      disabled>{{($caseData)?$caseData['case_description']:''}}</textarea>
                                            @error('cat_desc')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right" id="add">
                                        <button type="submit" id="add"
                                                class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Assign
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Table End -->
            </div>

            @include('cms.lawyerassignment.lawyerAssignmentDetailList')

        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->

@endsection


