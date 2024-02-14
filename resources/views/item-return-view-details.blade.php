@extends('layouts.app')

@section('content')
    {{-- view a single transferRecord order in detail --}}

    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <h1>Item</h1> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>                           
                            
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Item Return ID {{ $itemReturn->id }} : Item Return Details </h3>
                </div>
                <div class="card-body">

                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Item qty</th>
                                <th>From</th>
                                <th>Transfer To</th>
                                <th>Serial No</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemReturnDetailRecordList as $key => $itemReturnDetailRecord)
                                <tr>
                                    <td>{{ $itemReturnDetailRecord['item']['item_no'] }}<input class="returndetail_id" value="{{$itemReturnDetailRecord['id']}}"  id="return_details_id_{{$key+1}}" hidden/></td>
                                    <td>{{ $itemReturnDetailRecord['item']['description'] }}<input class="item_id" value="{{$itemReturnDetailRecord['item']['id']}}"  id="item_id_{{$key+1}}" hidden/></td>
                                    <td><input class="qty" value="{{$itemReturnDetailRecord->qty}}"  id="qty_{{$key+1}}" /></td>
                                    <td>{{ $itemReturnDetailRecord['returnfrom']['code'] }}</td>
                                    <td>
                                        @if($itemReturn->status == 2 )
                                        <select class="location_id" class="location_id" id='location_id_{{$key +1}}' name='location_id_{{$key +1}}' >
                                        <option value="">Select Location</option>
                                        @foreach($location as $l)
                                        <option @if($itemReturnDetailRecord['returnto']['id'] == $l->id) selected="selected" @endif value="{{$l->id}}">{{$l->code}} - {{$l->location}}</option>
                                        @endforeach
                                        </select>
                                        @else
                                        {{ $itemReturnDetailRecord['returnto']['code'] }}
                                        @endif
                                        
                                    </td>
                                    <td>{{ $itemReturnDetailRecord->serial_no }} <input class="serial_no" value="{{$itemReturnDetailRecord->serial_no}}"  id="serial_no_{{$key+1}}" hidden/></td>
                                </tr>
                            @endforeach
                            <input type="hidden" value="{{$key+1}}" id="row_count" />
                        </tbody>
                    </table>
                </div>
                @if ($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11 || $user['userrole']['id'] == 12)
                @if($itemReturn->status == 1)
                        <div>
                        <hr class="mr-3 ml-3">
                          <div class="d-flex justify-content-end m-3">
                            <span class="badge badge-danger" style="height: 20px;margin: 20px;">Pending</span>
                            <input type="button" class="btn btn-warning" value="Received To Zone Office" onclick="change_status(<?php echo $itemReturn->id ?>,2)"/>
                          </div>
                        </hr>
                        </div>
                    
                @elseif($itemReturn->status == 2)
                        <div>
                        <hr class="mr-3 ml-3">
                          <div class="d-flex justify-content-end m-3">
                          <span class="badge badge-warning" style="height: 20px;margin: 20px;">Zone Office</span> 
                          <input type="button" class="btn btn-info"  value="Transfer To Main" onclick="change_status(<?php echo $itemReturn->id ?>,3)"/>
                          </div>
                        </hr>
                        </div>  
                @elseif ($itemReturn->status == 3)
                        <div>
                        <hr class="mr-3 ml-3">
                          <div class="d-flex justify-content-end m-3">
                          <input type="hidden" id="transfer" value="{{ $itemReturn->id }}">
                          <span class="badge badge-info" style="margin: 15px;height: 20px;">Transfer To Main</span>
                                <button type="submit" class="btn btn-success" onclick="change_status(<?php echo $itemReturn->id ?>,3)">Confirm
                                    receival/Add Stock</button>
                          </div>
                        </hr>
                        </div>  
                @elseif($itemReturn->status == 4)
                        <div>
                        <hr class="mr-3 ml-3">
                          <div class="d-flex justify-content-end m-3">
                          <span class="badge badge-success">Received</span>
                          </div>
                        </hr>
                        </div> 
                    
                @endif
                @endif
            </div>
           
        </section>
    </div>
    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
    function change_status(id,status){

            
    let location_ids = [];
    let returndetail_ids = [];
    let item_ids = [];
    let serial_nos = [];
    var qtys =[];
    var row_count = $('#row_count').val();
    
    $('.location_id').each(function() {
        location_ids.push($(this).val());
    })
    $('.returndetail_id').each(function() {
        returndetail_ids.push($(this).val());
    })
    $('.item_id').each(function() {
        item_ids.push($(this).val());
    })
    $('.serial_no').each(function() {
        serial_nos.push($(this).val());
    })
    $('.qty').each(function() {
        qtys.push($(this).val());
    })

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
            });

    if(status == 3){
        var return_status=0;
        for(var i=1; i<= row_count; i++){
       var location =  $('#location_id_'+ i).val();
            if(location == ''){
            document.getElementById("location_id_"+ i).style.borderColor="red";
            return_status =1;
            }else{
                document.getElementById("location_id_"+ i).style.borderColor="green"; 
                return_status =0;
            }
        }
        
        if(return_status == 0){
            // ajax setup
            $.ajax({
                            url: "{{ url('/ir_status_update') }}",
                            method: "POST",
                            data: {
                                'id': id,
                                'status': status,
                                'location_ids': location_ids,
                                'returndetail_ids':returndetail_ids,
                                'serial_nos':serial_nos,
                                'item_ids':item_ids,
                                'qtys':qtys
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
 
    }else{
// ajax setup
            $.ajax({
                            url: "{{ url('/ir_status_update') }}",
                            method: "POST",
                            data: {
                                'id': id,
                                'status': status,
                                'location_ids': location_ids,
                                'returndetail_ids':returndetail_ids,
                                'serial_nos':serial_nos,
                                'item_ids':item_ids,
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
    
    location.reload();
            

        }
    </script>
@endsection
