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
                            <li class="breadcrumb-item active">Item Return</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        {{-- view card --}}

           
            <div class="card card-primary">
                    <div class="card-header">
                    <a href="{{ route('ItemReturnView') }}" class="btn btn-success">View Item Return List</a>
                    </div>
                    <div class="card-body p-4">
                        <!-- <h4 class="mb-2 text-bold">Logged in as a : <span class="text-primary">
                                {{ $user['userrole']['role'] }}</span></h4>
                        <p class="mb-3">viewable for :
                            @if ($user->role_id == 3)
                                <span class="text-primary text-bold">
                                    {{ $user['location']['code'] }}
                                </span>
                            @else
                                <span class="text-primary text-bold">ALL</span>
                            @endif
                        </p> -->
                        
                    </div>
                </div>
            

        <section class="content">
            <!-- general form elements disabled -->
            <div class="card card-primary">
                <!-- <div class="card-header">
                    <h3 class="card-title">Item Return</h3>

                </div> -->
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

                    <!-- <form action="{{ route('saveReturnItems') }}" method="POST" name="item_return"  enctype="multipart/form-data"> -->
                        <!-- @csrf -->
                        {{-- transfer order location selectors --}}
                        <div class="row">
                            {{--Return No --}}  
                           
                            <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Select Return Type : </label>
                                        <select class="form-control select2" name="type" id="type" onchange="change_type()">
                                            <option value="1">With Serial</option>
                                            <option value="2">Without Serial</option>
                                        </select>
                                    </div>
                          
                            </div>
                            <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Return No : </label>
                                       
                                        <input type="text" id="return_no" name="return_no" value="{{$nextid}}" class="form-control" readonly="true"/>
                                    </div>
                            </div>

                            {{--from location selector --}}
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>SERIAL NO</label>
                                     <input class="form-control select2" name="serialno" id="serialno" type="text" onkeyup="check_serial()">
                                    
                                    <!-- <select class="form-control select2" name="serialno" id="serialno">
                                        <option value="" disabled selected hidden>select serial no</option>
                                            @foreach($oldserial as $ols)
                                              <option value="{{$ols->serial_no}}">{{$ols->serial_no}}</option>
                                            @endforeach
                                    </select> -->
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Old Location</label>
                                     <input class="form-control" name="old_location" id="old_location" type="text" readonly="true" >
                                     <input class="form-control" name="old_location_id" id="old_location_id" type="hidden" >
                                </div>
                            </div>
                            
                        </div>
                        {{-- item selector --}}
                        <div class="row">
                        <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Qty</label>
                                     <input class="form-control" name="qty" id="qty" type="text" value="0" >
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>ITEM</label>                                  
                                    <select class="form-control select2" name="itemnunber" id="itemnunber" onclick="document.getElementById('serialno').value = ''">
                                        <option value="" disabled selected hidden>select item</option>
                                            @foreach($item as $se)
                                              <option value="{{$se->id}}">{{$se->item_no}} - {{$se->description}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        
                        {{-- serial selector --}}
                        <!-- <div class="row"> -->
                        <div class="col-sm-3">
                                <div class="form-group">
                                    <label>FROM : </label>
                                    @if ($user->role_id == 3)
                                    <label> {{$loginlocation->location}} </label>
                                    <input type="hidden" value="{{$loginlocation->id}}" name="transfer_order_from2" id="transfer_order_from2" />
                                    <input type="hidden" value="0" name="transfer_order_from" id="transfer_order_from" />
                                    @else
                                    <select  class="form-control" name="transfer_order_from" id="transfer_order_from">
                                        <option value="0" >Select From Location</option>
                                        @foreach($location as $lo)      
                                            <option value="{{ $lo->id }}">{{ $lo->location }}</option>                                                  
                                        @endforeach                                   
                                    </select>
                                    <input type="hidden" value="0" name="transfer_order_from2" id="transfer_order_from2" />
                                    @endif
                                </div>
                            </div>                            
                            
                            {{--to location selector --}}
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>TO</label>
                                    <select class="form-control" name="transfer_order_to" id="transfer_order_to">
                                    <option value="" >Select To Location</option>
                                        @foreach($location as $location)
                                        <!-- <option value="{{$location->id}}" >{{$location->location}}</option> -->
                                            @if($location->code == 'SBSTR-GPAS')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'ROFF-KANDY')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'ROFF-GALLE')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'MSGP')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'CENTERETRN')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'ITRTNGP')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'TVSCT-GPSS')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'TV/RM/MNT')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'ITD/GP')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                            @if($location->code == 'MSGP Store - Return')
                                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            @endif
                                           
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>                        
                        <button type="button" class="btn btn-info btn-sm text-bold" onclick="append_trs()"><span class="fas fa-plus mr-2"></span>ADD</button>
                        </div>
                </div>
                <section class="content">
                    <!-- general form elements disabled -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Configure return items</h3>
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
                        </div>

                        <div class="card card-default">
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
                                                    <h3 class="card-title">Add items</h3>
                                                </div>
                                                <div class="card-body">


                                                    <table  class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="table-success">Code</th>
                                                                <th class="table-success">Description</th>
                                                                <th class="table-success">QTY</th>
                                                                <th class="table-success text-center" >Serial</th>
                                                                <th class="table-success text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody">
                                                        </tbody>
                                                        <input type="hidden" name="row_count" id="row_count" value="0">
                                                    </table>
                                                </div>
                                            </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="d-flex justify-content-end mr-3">
                            <button  onclick="submitReturnDetail()" class="btn btn-success">Submit</button>
                        </div>
                        <!-- /.card-footer -->
                </section>

                <!-- </form> -->

                <!-- /.card-body -->
            </div>
        </section>
    </div>

</div>

<!-- imports -->
{{-- re importing jQuery because it won't load for some reason  --}}
<script src="plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
      $(document).ready(function() {
            // $(".chosen-select").chosen({ rtl: true });
             $('#transfer_order_from').select2();
             $('#itemnunber').select2();
             
        });

        $('#itemnunber').focus();

        function change_type(){
            var type = $('#type').val();

            ////Refresh Data
            $('#qty').val(0);
            $('#old_location').val(0);
            $('#old_location_id').val(0);
            $('#serialno').val(' ');
            $('#transfer_order_from').val(0).trigger("change");
            $('#itemnunber').val(0).trigger("change");


            if(type == 2){
                document.getElementById('qty').readOnly = false;
                document.getElementById('serialno').readOnly = true;
            }else{
                document.getElementById('qty').readOnly = true;
                document.getElementById('serialno').readOnly = false;
                $('#qty').val(1);
            }
           
        }

        function check_serial(){
            var serialno = $('#serialno').val();
            $.ajax({
                url: "{{ url('return/setvalue') }}",
                method: "GET",
                data: {
                    "serialno": serialno
                },
                success: function(data) {
                    console.log(data.data.length);
                    if(data.data.length > 0){
                        $('#qty').val(1);
                        $('#old_location').val(data.data[0].location.location);
                        $('#old_location_id').val(data.data[0].location.id);
                        $('#transfer_order_from').val(data.data[0].location.id).trigger("change");
                        $('#itemnunber').val(data.data[0].item.id).trigger("change");
                    }else{
                        $('#old_location').val(' ');
                        $('#old_location_id').val(' ');
                        $('#transfer_order_from').val(' ').trigger("change");
                        $('#itemnunber').val(' ').trigger("change");
                        $('#qty').val(1);
                    }
                    
                    // $('#um').val(data.data[0].uom);
                    // $('#item_transfer_qty').val(0);

                },
            });
        }

        function validate() {
            var transfer_order_from = document.item_return.transfer_order_from.value;
            if(transfer_order_from==""){
                alert("select from location");
                document.item_return.transfer_order_from.focus();
                return false;           
            }else{
                alert("successfull");
                return true;
            }
            
        }
</script>


<script>

// called when changing the value of the item selector
// ---------------------------------------------------

var all_data = [];

function setvalue() {

   var id = $('#item').val();

    // TODO: on item select :

    // grab the source warehouse value from the source warehouse selector
    // match the item stock with the item id
    // and load the stock
    // and make sure the item transfer quantity won't exceed the stock count

    $('#item_description').val(id);
    $('#item_stock').val(0);
    $('#item_transfer_qty').val(0);

    // figure out what this code is

    $.ajax({
        url: "{{ url('return/setvalue') }}",
        method: "GET",
        data: {
            "id": id
        },
        success: function(data) {
            console.log(data.data[0].item_code);
            $('#itemcategory').val(data.data[0].item_code);
            $('#um').val(data.data[0].uom);
            $('#item_transfer_qty').val(0);

        },
    });
}

function get_item_details(){
     $.ajax({
        url: "{{ url('order/get_item_details') }}",
        method: "GET",
        success: function(data) {

        },
    });
}


//

// called on pressing the ADD button. Appends item row
// ---------------------------------------------------

function append_trs(){
    var serialno = $('#serialno').val(); 
    var id = $('#itemnunber').val();
    var qty = $('#qty').val();
    var type = $('#type').val();
    var from = $('#transfer_order_from').val();
    var from2 = $('#transfer_order_from2').val();
    var to = $('#transfer_order_to').val();
    
    if(from2 == 0){
        var from_location = from;
    }else{
        var from_location = from2;
    }
    let status = true;
    if(type == 1){
        if(id == null || serialno != '' || from_location == 0 || to == '' || qty == 0){
            document.getElementById("serialno").style.border="1px solid red";
            document.getElementById("qty").style.border="1px solid red";
            $("#transfer_order_from").each(function() {
            $(this).siblings(".select2-container").css('border', '1px solid red');
            });
            $("#itemnunber").each(function() {
            $(this).siblings(".select2-container").css('border', '1px solid red');
            });
            document.getElementById("transfer_order_to").style.border="1px solid red";   
            status = false;
        }else{
            status = true;
        }       
    }
    if(type == 2){
        if(qty > 0 || id == null ||  from_location == 0 || to == ''){
            console.log(status);
            document.getElementById("qty").style.border="1px solid red";
            $("#transfer_order_from").each(function() {
            $(this).siblings(".select2-container").css('border', '1px solid red');
            });
            $("#itemnunber").each(function() {
            $(this).siblings(".select2-container").css('border', '1px solid red');
            });
            document.getElementById("transfer_order_to").style.border="1px solid red";   
            status = false;
        }else{
            status = true;
        }
    }

   
    if(!status){
        $.ajax({
        url: "{{ url('getitemdetails') }}",
        method: "GET",
        data: {
            "id": id
        },
        success: function(data) {            
           console.log(data.data);
           if(data.data.item_type_id == 1 && serialno == ''){
            document.getElementById("serialno").style.borderColor="red";
            Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Serial empty",
                    text: "Please fill a Serial No",
                    showConfirmButton: 1,
                });
           }else{
            document.getElementById("serialno").style.borderColor="green";
            var row_count = $('#row_count').val();
            $('#row_count').val(parseInt(row_count)+1);

            $('#tbody').append('<tr id=tr_"'+ row_count +'">'+
                                
                                //'<td> <input type="text" name="testting[]" value="'+ data.data[0]['item']['item_no'] +'"/></td>'+
                                '<td>'+ data.data.item_no +'<input class="item_id" type="hidden" value="'+ data.data.id +'" id="item_id_'+ row_count +'" name="item_id_'+ row_count +'" /></td>'+
                                '<td>'+ data.data.description +'<input class="item_description" type="hidden" value="'+ data.data.description +'" id="description_id_'+ row_count +'" name="description_id_'+ row_count +'" /></td>'+
                                '<td>'+ qty +'<input class="qty" type="hidden" value="'+ qty +'" id="qty_'+ row_count +'" name="qty_'+ row_count +'" /></td>'+
                                '<td>'+ serialno +'<input class="serial_no" type="hidden" value="'+ serialno +'" id="serial_id_'+ row_count +'" name="serial_id_'+ row_count +'" /><input class="from_location" type="hidden" value="'+ from_location +'" /><input class="to_location" type="hidden" value="'+ to +'" /></td>'+
                                '<td style="text-align:right"><button type="button" class="btn btn-primary btn-sm" onclick="delete_row(this)">-</button></td>'+
                                '</tr>');

           }
            
            // }//
                        
                }
    });
    }
    



};
//tbl_return_detail

function submitReturnDetail(){

    var from = $('#transfer_order_from').val();
    var to = $('#transfer_order_to').val();
    var return_no = $('#return_no').val();
    //var itemno = $('#itemnunber').val(); 


    // testing
    //console.log(itemno);

    var item_ids = [];
    var serial_numbers = [];
    var qtys = [];
    var froms = [];
    var tos = [];
    
    $('.item_id').each(function() {
        item_ids.push($(this).val());
    })

    $('.serial_no').each(function() {
        serial_numbers.push($(this).val());
    })

    $('.qty').each(function() {
        qtys.push($(this).val());
    })
    $('.from_location').each(function() {
        froms.push($(this).val());
    })
    $('.to_location').each(function() {
        tos.push($(this).val());
    })
    console.log(serial_numbers);

    // ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
    });
if(to == ''){
    document.getElementById("transfer_order_to").style.borderColor="red";
}else{
    $.ajax({
        url: "{{ url('savereturnitems') }}",
        method: "POST",
        data: {
            'item_ids': item_ids,
            'serial_numbers': serial_numbers,
            'from': froms,
            'to':tos,
            'return_no' :return_no,
            'qtys': qtys
        },
        success: function(response) {
            if(response.status == 500){
                Swal.fire({
                                        icon: 'error',
                                        type: "error",
                                        title: response.message,
                                        footer: response.error,
                                        showConfirmButton: 1
                                    });
            }else {
                Swal.fire({
                                        icon: 'Return Added Success',
                                        type: "success",
                                        title: response.message,
                                        showConfirmButton: 1
                                    }).then(function() {
                                        location.reload();
                                    });
            }
           
        },
    });
     location.reload();
}

}


function delete_row(btn){
   var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

var row_count = $('#row_count').val();
   $('#row_count').val(parseInt(row_count) - 1);

}

function change_order_status(orderid,storestatus,center_status){

    var referenceno = '';
    if(storestatus == 1 ){
        console.log(storestatus);
        referenceno = $('#referenceno_'+ orderid).val();
        $("#referenceno_" + orderid).css("border", "1px solid red");
        $("#referenceno_" + orderid).focus();

    }else{
        $("#referenceno_" + orderid).css("border", "1px solid green");
        $("#referenceno_" + orderid).focus();
    }

        const formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('orderid', orderid);
        formData.append('storestatus', storestatus);
        formData.append('center_status', center_status);
        formData.append('referenceno', referenceno);
    
}

</script>



@endsection