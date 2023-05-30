<div class="card">
    <div class="card-header">
        <h4 class="card-title">Category List</h4>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <div class="table-responsive">
                <table class="table table-sm datatable mdl-data-table dataTable" id="category_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>CATEGORY NAME</th>
                        <th>DESCRIPTION</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--@foreach($caseCategory as $key=>$lot)
                        <tr>
                            <td>{{++$key}}.</td>
                            <td>{{$lot->category_name}}</td>
                            <td>{{$lot->description}}</td>
                            <td>{{$lot->active_yn == "Y" ? 'ACTIVE' : 'INACTIVE'}}</td>
                            <td style="display:none;">{{$lot->category_id}}</td>
                            <td>

                                <a href="{{ url('/case-category/edit') }}/{{$lot->category_id}}"
                                   class="table-action-btn" title=" Edit">
                                    <i class="bx bx-edit cursor-pointer"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('footer-script')
    <!--Load custom script-->
    <script>
        $("#reset").click(function () {
            $("#case_category_form").trigger('reset');
            $('.select2').val('').trigger('change');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            categorylist();
        });

        function categorylist()
        {
            $('#category_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/case-category-dataTable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": 'category_name', "name": 'category_name'},
                    {"data": 'description', "name": 'description'},
                    {"data": 'status', "name": 'status'},
                    {"data": 'action', "name": 'action'},
                ]
            });
        }
    </script>
@endsection
