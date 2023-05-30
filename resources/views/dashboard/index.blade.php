@extends('layouts.default')

@section('title')
    Dashboard
@endsection

@section('header-style')
    <style type="text/css">
        .swiper-container {
            padding: 0px 130px;
        }

        .swiper-slide {
            width: 60%;
            min-height: 200px;
        }

        .swiper-slide:nth-child(2n) {
            width: 40%;
        }

        .swiper-slide:nth-child(3n) {
            width: 20%;
        }

        .card.cardSlide {
            min-height: 180px;
        }

        .first_row {
            min-height: 530px;
        }

        .second_row {
            min-height: 275px;
        }

        .third_row {
            min-height: 500px;
        }

        .forth_row {
            min-height: 410px;
        }

        @media only screen and (max-width: 1400px) {
            .swiper-container {
                padding: 0px !important;
            }

            .swiper-slide {
                width: 50%;
                min-height: 200px;
            }

            .swiper-slide:nth-child(2n) {
                width: 50%;
            }

            .swiper-slide:nth-child(3n) {
                width: 50%;
            }

            .swiper-slide h5 {
                font-size: 15px !important;
            }

            #dashboard-analytics h2 {
                font-size: 22px !important;
            }

            #dashboard-analytics h4, #dashboard-analytics h6 {
                font-size: 15px !important;
            }

            #dashboard-analytics span {
                font-size: 12px;
            }

            #dashboard-analytics table tr th {
                font-size: 13px;
            }
        }

        @media only screen and (max-width: 640px) {
            .swiper-container {
                padding: 0px !important;
            }

            .swiper-slide {
                width: 100% !important;
                min-height: 200px;
            }

            .swiper-slide:nth-child(2n) {
                width: 100% !important;
            }

            .swiper-slide:nth-child(3n) {
                width: 100% !important;
            }

            .shadow-lg.p-2 {
                padding: 0 !important;
            }
        }
    </style>
@endsection

@section('content')
    <section id="dashboard-analytics">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Welcome to CPA Case Management System</h4>
                        <hr>
                        <form method="POST" id="search-form" name="search-form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-4">

                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="input-group date" id="caseDateDP"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   value=""
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
                                <div class="col-sm-1 mr-2">
                                    <button type="submit" class="btn btn btn-dark shadow btn-secondary">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-sm datatable mdl-data-table" id="final-results">
                                <thead>
                                <tr>
                                    <th style="height: 25px;text-align: left; width: 5%">Sl.
                                    </th>
                                    <th style="height: 25px;text-align: left; width: 35%">Case Name
                                    </th>
                                    <th style="height: 25px;text-align: left; width: 35%">
                                        Court
                                    </th>
                                    <th style="height: 25px;text-align: left; width: 20%">
                                        Last Update
                                    </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Table Start -->
                    <div class="card-body">
                        <h4 class="card-title text-center">Important Links</h4><!---->
                        <hr>
                        <li><a href="http://www.supremecourt.gov.bd/web/" target="_blank">Supreme Court of Bangladesh</a></li>
                        <li><a href="https://minlaw.gov.bd/" target="_blank">Ministry of Law, Justice and Parliamentary Affairs</a></li>
                        <li><a href="https://mos.gov.bd/" target="_blank">Ministry of Shipping</a></li>
                        <li><a href="http://www.judiciary.org.bd/en" target="_blank">Judicial Portal Bangladesh</a></li>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($cases))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Table Start -->
                    <div class="card-body">
                        <h4 class="card-title text-center">Case Reminder</h4><!---->
                        <hr>
                        @foreach($cases as $case)
                            <li>Case Name: {{$case->case_no}}, Description: {{$case->case_description}} is in 2 days({{date('d-M-y', strtotime($case->next_date))}}).</li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

    </section>

@endsection

@section('footer-script')
    <script type="text/javascript">
        var options = {
            series: [{
                name: "STOCK ABC",
                data: [100, 200, 300, 400]
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },

            title: {
                text: 'Fundamental Analysis of Stocks',
                align: 'left'
            },
            subtitle: {
                text: 'Price Movements',
                align: 'left'
            },
            labels: ['01/01/2019', '02/02/2019', '03/03/2019', '04/04/2019'],
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                opposite: true
            },
            legend: {
                horizontalAlign: 'left'
            }
        };

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

        var oTable = $('#final-results').DataTable({
            processing: true,
            serverSide: true,
            bDestroy: true,
            pageLength: 10,
            bFilter: true,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            ajax: {
                url: APP_URL + "/dashboard-search-data",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.case_date = $('#case_date').val();
                }
            },
            "columns": [
                {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                {"data": "case_name"},
                {"data": "court_name"},
                {"data": "last_update_speech"},
            ]
        });

        $(document).ready(function () {

            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10)
                month = "0" + month;
            if (day < 10)
                day = "0" + day;
            var today =  day+ '-'+ month+ '-'+ now.getFullYear();
            $('#case_date').val(today);

            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                oTable.draw();
            });
        });

    </script>
@endsection
