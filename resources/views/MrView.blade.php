@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div style="min-height: 1345.31px;">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <!-- <h1>Item</h1> -->
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">MR Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">MR Report</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-12 form-grop">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">MR Report</h3>
                                        <div style="float: right;">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table  class="table table-striped table-bordered" id="dataTable"> >
                                            <thead>
                                                <tr>
                                                    <th>Order No</th>
                                                    <th>location_id</th>
                                                    <th>status</th>
                                                    <th>created_at</th>
                                                    <th>Action</th>
                                                    <th>Link</th>
                                                    <!-- <th Colspan="2">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ods as $od)
                                                    <tr>
                                                        <td>{{ $od->orderno }}</td>
                                                        <td>{{ $od['location']['code'] }}</td>
                                                        <td>
                                                            @if($od->status == 1)
                                                                <span class="badge badge-danger">Pending</span>
                                                            @elseif($od->status == 2 || $od->status == 3)  
                                                            <span class="badge badge-info">Processing</span>
                                                                @if($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11 )
                                                                    <input type="button" class="btn btn-success"  value="Closed" onclick="change_status(<?php echo $od->id ?>,4)"/>
                                                                @endif
                                                            @elseif($od->status == 4)
                                                                <span class="badge badge-success">Closed By Stores</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td>{{ $od->created_at }}</td>
                                                        <td><a href="{{ route('mrdetail',['od' => $od]) }}"
                                                                class="btn btn-primary btn-sm">View</a>
                                                            </td>
                                                            @if($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11 )    
                                                            <td><a href="{{ route('transfer_order',['od' => $od]) }}"
                                                                class="btn btn-warning btn-sm">Link TR</a>
                                                            @else
                                                            <td></td>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                      

                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
            </section>
        </div>

    </div>

    <script>

        function change_status(id,status){
            // ajax setup
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
            });

            $.ajax({
                            url: "{{ url('/mr_status_update') }}",
                            method: "POST",
                            data: {
                                'id': id,
                                'status': status
                            },
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: 'success',
                                        type: "success",
                                        title: response.message,
                                        showConfirmButton: 1
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else if (response.status == 500) {
                                    Swal.fire({
                                        icon: 'error',
                                        type: "error",
                                        title: response.message,
                                        footer: response.error,
                                        showConfirmButton: 1
                                    });
                                }
                            },
                        });

        }

        function onMRSelect() {

let MR = $('#transfer_order_mr').val();

if (MR != null) {

    $.ajax({
        url: "{{ url('mrlocation') }}",
        method: "GET",
        data: {
            "MR": MR,
        },
        success: function(data) {
            $('#transfer_order_to').val(data.data[0]['location']['id']).trigger("change");
        },
    });

    $.ajax({
        url: "{{ url('mrdetails') }}",
        method: "GET",
        data: {
            "MR": MR,
        },
        success: function(data) {
           console.log(data.data);
        let row_count = $('#row_count').val();
        $('#row_count').val(parseInt(data.data.length) + 1);
        for(var x=0; x < data.data.length; x++){
            var qtydif = data.data[x]['center_request_qty'] - data.data[x]['grn_qty'];
            $('#transfer_order_item_table_body').append('<tr id=tr_"' + x + '">' +
        '<td>' + data.data[x]['item_no'] + data.data[x]['description']   +'<input class="item" type="hidden" value="' + data.data[x]['item_id'] + '" id="item_id_' +
        x +
        '" name="item_id_' + x + '" /> <input class="item_type" type="hidden" value="' + data.data[x]['item_type_id'] +
        '" id="item_type_' + x + '" /></td>' +
        '<td style="text-align:center">'+ data.data[x]['stockqty'] +'</td>'+ 
        '<td style="text-align:center">'+ data.data[x]['center_request_qty'] +'</td>'+ 
        '<td style="text-align:center">'+ data.data[x]['grn_qty'] +'</td>'+ 
        '<td style="text-align:center">' + 
        '<input style="width: 30%;text-align: center;" type="number" min="0"   required="true" class="qty" type="text" value="' +
        qtydif + '" id="qty_' + x + '" name="qty_' + x + '" class="form-control"/></td>' +
        '<td style="text-align:center"><input class="serials" type="hidden" value="" id="serials_' + x + '" name="serials_' + x +
        '" /></td>' +
        '<td style="text-align:center"></td>' +
        '</tr>');
            }
            if(qtydif == 0){
                document.getElementById("qty_" + x).readOnly = true;
            }
            $('#direct_item_table').hidden();
            // $('#transfer_order_mr_details').html(data);
        },
    });
}
}
    </script>

@endsection
