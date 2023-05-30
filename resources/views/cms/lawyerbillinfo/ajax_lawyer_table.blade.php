@php $total = 0; @endphp
            <div class="card">
                <div class="card-body"><h4 class="card-title">Bill Submission List</h4>
                    <div class="table-responsive">
                        <form action="{{ url('save-bill') }}" method="POST">
                            @csrf
                        <table class="table table-sm datatable mdl-data-table" id="final-results">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>SL.</th>
                                <th>DATE</th>
                                <th>CASE NO.</th>
                                <th>SERVICE NAME</th>
                                <th>DEMANDED AMOUNT</th>
                                <th>PAYABLE AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($searchInfo))
                                @foreach($searchInfo as $key=>$value)
                                    @php $total = $total + $value->bill_amount; @endphp
                                    <tr>
                                        <td>
                                            <input type='checkbox' name='checkbox[]' value="{{$key}}" onclick="recalculate(this)" checked="checked">
                                            <input type='hidden' name='bill_id[]' value="{{$value->bill_id}}">
                                        </td>
                                        <td>{{++$key}}</td>
                                        <td>{{$value->service_date}}
                                            <input type="hidden" class="form-control"
                                                   name="service_date[]"
                                                   value="{{$value->service_date}}"></td>
                                        <td>{{$value->case_no}}
                                            <input type="hidden" class="form-control"
                                                   name="case_no[]"
                                                   value="{{$value->case_no}}"></td>
                                        <td>{{$value->service_name}}
                                            <input type="hidden" class="form-control"
                                                   name="service_name[]"
                                                   value="{{$value->service_name}}"></td>
                                        <td><input type="text" class="form-control" style="text-align:right"
                                                   name="bill_amount[]" style="text-align:right"
                                                   value="{{$value->bill_amount}}" readonly></td>
                                        <td><input type="text" class="form-control" style="text-align:right"
                                                   name="approve_amount[]"
                                                   value="{{$value->bill_amount}}" readonly></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <hr>
                            <tfoot style="border-top: 2px solid #dfe3e7; border-bottom: 2px solid #dfe3e7;">
                            <tr>
                                <td id="total" style="text-align:right" colspan="5">Total :</td>
                                <td><input type="text" name="total_bill" id="total_bill" value="{{$total}}" class="form-control" style="text-align:right" readonly></td>
                                <td><input type="text" name="total_approve" id="total_approve" value="{{$total}}" onkeyup="checkValue();" class="form-control" style="text-align:right"></td>
                            </tr>
                            </tfoot>
                        </table>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <div class="d-flex justify-content-end col">
                                        <button type="submit" class="btn btn btn-dark shadow mb-1 btn-secondary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<script type="text/javascript">

    function recalculate(item){
        var total = parseInt($('#total_bill').val());
        var input = document.getElementsByName('approve_amount[]');

        // if(Number.isNaN(total)){
        //     total = parseInt($('#total_bill').val());
        // }
        if(item.checked){
            total += parseInt(input[item.value].value);
        }else{
            total -= parseInt(input[item.value].value);
        }
        $('#total_approve').val(total);
        $('#total_bill').val(total);
    }

    // function calculate(){
    //     var input = document.getElementsByName('approve_amount[]');
    //     var checkbox = document.getElementsByName('checkbox[]');
    //     var total = 0;
    //
    //     for (let i = 0; i < input.length; i++) {
    //         if(checkbox[i].checked){
    //             if(input[i].value){
    //                 total += parseInt(input[i].value);
    //             }
    //         }
    //     }
    //     $('#total_approve').val(total);
    // }

    function checkValue(){
        var bill_total = parseInt($('#total_bill').val());
        var approve_total = parseInt($('#total_approve').val());
        // alert('approve = '+approve_total+' bill = '+bill_total);
        if(approve_total > bill_total)
        {
            alert('Total approve amount cannot be greater than total bill amount');
            $('#total_approve').val(bill_total);
        }
    }

    $(document).ready(function () {
        $('#final-results').DataTable({
            "columnDefs": [
                { "searchable": true, "targets": 0 }
            ],
            "paging":   false,
            "aaSorting": []
        });
        // $('.dataTables_length').addClass('bs-select');
    });
</script>
