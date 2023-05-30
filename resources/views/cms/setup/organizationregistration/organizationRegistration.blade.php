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
                    <h4 class="card-title">Organization Registration</h4><!---->
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
                          action="{{($editData)?url('/organization-registration/edit/'.$editData['organization_id']):url('/organization-registration')}}"
                          method="post" id="organization_form">

                        @csrf
                        @include('cms.partials.orgRegistation')

                        <script type="text/javascript"
                                src="{{asset('assets/plugins/switchery/js/switchery.min.js')}}"></script>
                    </form>

                </div>
                <!-- Table End -->

            </div>
            @include('cms.setup.organizationregistration.organizationList')

        </div>
    </div>


@endsection

@section('footer-script')
    <!--Load custom script-->


@endsection
