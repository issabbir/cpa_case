<div class="card">

    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>CASE NO</th>
                        <th>LAWYER NAME</th>
                        <th>ASSIGN DATE</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($gridData as $key=>$lot)
                        <tr>
                            <td>{{++$key}}.</td>
                            <td>{{$lot->case_no}}</td>
                            <td>{{$lot->lawyer_name}}</td>
                            <td>{{$lot->assign_date}}</td>
                            <td>{{$lot->case_status_name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-right" id="add">
                    <a href="{{url('/lawyer-assignment')}}">
                        <button type="submit" id="reset"
                                class="btn btn btn-outline shadow mb-1 btn-secondary">Exit
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
