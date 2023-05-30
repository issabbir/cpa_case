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
                    <h4 class="card-title">Case Category</h4>
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
                          action="{{($editData)?url('/case-category/edit/'.$editData['category_id']):url('/case-category')}}"
                          id="case_category_form"
                          method="post">

                        @csrf
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="required">Category Name</label>
                                            <input required type="text" id="category_name" name="category_name"
                                                   class="form-control" placeholder="Category Name"
                                                   value="{{old('category_name',($editData)?$editData['category_name']:'')}}"
                                                   autocomplete="off">
                                            @if(!empty($editData))
                                                <input type="hidden" id="category_id" name="category_id"
                                                       class="form-control"
                                                       value="{{($editData)?$editData['category_id']:''}}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category Name (Bangla)</label>
                                            <input type="text" id="category_name_bng" name="category_name_bng"
                                                   class="form-control" placeholder="Category Name (Bangla)"
                                                   value="{{old('category_name_bng',($editData)?$editData['category_name_bng']:'')}}"
                                                   autocomplete="off">
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
                                                      name="cat_desc"
                                                      class="form-control"
                                                      id="instructions">{{old('cat_desc',($editData)?$editData['description']:'')}}</textarea>
                                            @error('cat_desc')<span class="text-danger">{{ $message }}</span>@enderror
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
                                    <a href="{{url('/case-category')}}">
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
            </div>

            @include('cms.setup.casecategory.categoryList')

        </div>
    </div>
@endsection



