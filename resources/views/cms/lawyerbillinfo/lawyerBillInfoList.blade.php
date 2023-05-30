@if(!empty($searchInfo))
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Bill Submission List</h4>
        </div>
        <div class="card-content">
            <div class="card-body card-dashboard">
                <div class="table-responsive">
                    <table class="table table-sm datatable mdl-data-table dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>CASE NO.</th>
                            <th>COMPLAINANT</th>
                            <th>DEFENDENT</th>
                            <th>SERVICE NAME</th>
                            <th>SERVICE DATE</th>
                            <th>COURT NAME</th>
                            <th>RATE CHART</th>
                            <th>AMOUNT</th>
                            <th style="display:none;">
                            <th style="display:none;">
                            <th style="display:none;">
                            <th style="display:none;">
                            <th style="display:none;">
                            <th style="display:none;">
                            <th>ACTION</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($searchInfo as $key=>$lot)
                            <tr>
                                <td>{{++$key}}.</td>{{--1--}}
                                <td class="case_no_name">{{$lot->case_no_name}}</td>{{--2--}}
                                <td class="complainant_badi_name">{{$lot->complainant_badi_name}}</td>{{--3--}}
                                <td class="defendant_bibadi_name">{{$lot->defendant_bibadi_name}}</td>{{--4--}}
                                <td class="service_name">{{$lot->service_name}}</td>{{--5--}}
                                <td class="service_date">{{--{{ date('d-m-Y', strtotime($lot->service_date)) }}--}}{{ $lot->service_date }}</td>{{--6--}}
                                <td class="court_name">{{$lot->court_name}}</td>{{--7--}}
                                <td class="rate_chart">{{$lot->rate_chart}}</td>{{--8--}}
                                <td class="amount" style="text-align:right">{{$lot->amount}}</td>{{--9--}}
                                <td style="display:none;"
                                    class="lawyer_name">{{$lot->lawyer_name}}</td>{{--10--}}
                                <td style="display:none;"
                                    class="description">{{$lot->description}}</td>{{--11--}}
                                <td style="display:none;" class="case_id">{{$lot->case_id}}</td>{{--12--}}
                                <td style="display:none;"
                                    class="rate_chart_id">{{$lot->rate_chart_id}}</td>{{--13--}}
                                <td style="display:none;"
                                    class="lawyer_id">{{$lot->lawyer_id}}</td>{{--14--}}
                                <td style="display:none;"
                                    class="assignment_type_id">{{$lot->assignment_type_id}}</td>
                                <td style="display:none;"
                                    class="comments">{{$lot->bill_comments}}</td>
                                <td> {{--<button type="button" class="btn btn-primary" data-toggle="modal" id="edit_category" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>--}}
                                    <a class="table-action-btn mr-1 edit" id="edit_category" title=" Edit"
                                       style="cursor:pointer;" data-toggle="modal"
                                       data-target="#exampleModal">
                                        <i class="bx bx-edit cursor-pointer"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
