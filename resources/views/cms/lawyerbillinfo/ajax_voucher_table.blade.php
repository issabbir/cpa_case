@php $total = 0; @endphp
<div class="card">
    <div class="card-body"><h4 class="card-title">Bill Voucher List</h4>
        <div class="table-responsive">
{{--            <form action="{{ url('save-bill') }}" method="POST">--}}
                @csrf
                <table class="table table-sm datatable mdl-data-table" id="final-results">
                    <thead>
                    <tr>
                        <th>SL.</th>
                        <th>VOUCHER NO.</th>
                        <th>DEMANDED AMOUNT</th>
                        <th>PAYABLE AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($searchInfo))
                        @foreach($searchInfo as $key=>$value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$value->voucher_no}}</td>
                                <td style="text-align: right">{{$value->bill_amount}}</td>
                                <td style="text-align: right">{{$value->bill_approve_amount}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
{{--            </form>--}}
        </div>
    </div>
</div>

<script type="text/javascript">

    // function recalculate(item){
    //     var total = parseInt($('#total_bill').val());
    //     var input = document.getElementsByName('approve_amount[]');
    //
    //     // if(Number.isNaN(total)){
    //     //     total = parseInt($('#total_bill').val());
    //     // }
    //     if(item.checked){
    //         total += parseInt(input[item.value].value);
    //     }else{
    //         total -= parseInt(input[item.value].value);
    //     }
    //     $('#total_approve').val(total);
    //     $('#total_bill').val(total);
    // }

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

    // function checkValue(){
    //     var bill_total = parseInt($('#total_bill').val());
    //     var approve_total = parseInt($('#total_approve').val());
    //     // alert('approve = '+approve_total+' bill = '+bill_total);
    //     if(approve_total > bill_total)
    //     {
    //         alert('Total approve amount cannot be greater than total bill amount');
    //         $('#total_approve').val(bill_total);
    //     }
    // }

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
