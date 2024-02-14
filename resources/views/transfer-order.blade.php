@extends('layouts.app')

@section('content')

    {{-- transfer order page --}}

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
                                <li class="breadcrumb-item active">Transfer Orders</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                {{-- view card --}}

                <div class="card card-primary">
                    <div class="card-header">
                    <a href="{{ route('view_all_transfer_orders') }}" class="btn btn-success">View Transfer Order List</a>
                    </div>
                    <div class="card-body p-4">
                        <!-- <h4 class="mb-2 text-bold">Logged in as a : <span class="text-primary">
                                {{ $user['userrole']['role'] }}</span></h4>
                        <p class="mb-3">Transfer orders viewable for :
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

                <div class="mt-4 mb-4">
                    <hr>
                </div>

                <!-- general form elements disabled -->
                <div class="card card-info">
                    <!-- <div class="card-header">
                        <h3 class="card-title">Configure transfer order</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row">

                            {{-- column left --}}

                            <div class="col-sm-12 border-right pr-3">

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
                                {{-- <form action="" method="POST" enctype="multipart/form-data"> --}}
                                {{-- @csrf --}}

                                {{-- transfer order type selector --}}
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Transfer order type</label>
                                            @if($mr)
                                                <select class="form-control" name="transfer_order_type" id="transfer_order_type"
                                                    onchange="onTransferOrderTypeSelect()">
                                                    <option value="" disabled selected hidden>Select type</option>
                                                    <option value=1>Direct</option>
                                                    <option value=2 selected>MR</option>
                                                </select>
                                            @else
                                                    <select class="form-control" name="transfer_order_type" id="transfer_order_type"
                                                        onchange="onTransferOrderTypeSelect()">
                                                        <option value="" disabled selected hidden>Select type</option>
                                                        <option value=1>Direct</option>
                                                        <option value=2 >MR</option>
                                                    </select>
                                            @endif
                                           
                                        </div>
                                    </div>
                                    {{-- transfer order MR select --}}
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Transfer order MR no</label>
                                            @if($mr)
                                            <select class="form-control" name="transfer_order_mr" id="transfer_order_mr"
                                                disabled onchange="onMRSelect()">
                                                <option value="" disabled selected hidden>Select order num
                                                </option>
                                                @foreach ($orderlist as $order)
                                                    <option value="{{ $order->id }}" @if($mr[0]['id'] == $order->id) selected="selected" @endif>{{ $order->orderno }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @else
                                            <select class="form-control" name="transfer_order_mr" id="transfer_order_mr"
                                                disabled onchange="onMRSelect()">
                                                <option value="" disabled selected hidden>Select order num
                                                </option>
                                                @foreach ($orderlist as $order)
                                                    <option value="{{ $order->id }}">{{ $order->orderno }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @endif
                                            
                                        </div>
                                    </div>

                                    {{-- source location selector --}}
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Source location / FROM</label>
                                            <select class="form-control" name="transfer_order_from" id="transfer_order_from"
                                                onchange="onSourceLocationSelect(this.value)">
                                                <option value="" disabled selected hidden>from</option>
                                                @foreach ($locationlist as $location)
                                                        @if($location->code == 'SBSTR-GPAS' && $user->role_id == 12)
                                                        <option value="{{ $location->id }}" selected>
                                                            {{ $location->code }} - {{ $location->location }}</option>
                                                    @elseif ($location->code == 'MSGP' && $user->role_id != 12)
                                                        <option value="{{ $location->id }}" selected>
                                                            {{ $location->code }} - {{ $location->location }}</option>
                                                    @else
                                                        <option value="{{ $location->id }}">{{ $location->code }} -
                                                            {{ $location->location }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- target location selector --}}
                                    <div class="col-sm">

                                        <div class="form-group">
                                            <label>Target location / TO</label>
                                            @if($mr)
                                            <select class="form-control" name="transfer_order_to" id="transfer_order_to"
                                                onchange="onTargetLocationSelect(this.value)">
                                                <option value="" disabled selected hidden>to</option>
                                                @foreach ($locationlist as $location)
                                                    <option  value="{{ $location->id }}" @if($mr[0]['location_id'] == $location->id) selected="selected" @endif>{{ $location->code }} -
                                                        {{ $location->location }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @else
                                            <select class="form-control" name="transfer_order_to" id="transfer_order_to"
                                                onchange="onTargetLocationSelect(this.value)">
                                                <option value="" disabled selected hidden>to</option>
                                                @foreach ($locationlist as $location)
                                                    <option value="{{ $location->id }}">{{ $location->code }} -
                                                        {{ $location->location }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- </form> --}}
                                <div class="row">

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label>Remark</label>
                                        <textArea id="remark" class="form-control"></textArea>
                                        </div>
                                    </div>     
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label>Help Desk No.</label>
                                        <input type="text" id="helpdeskno" class="form-control">

                                        </div>
                                    </div>             
                                </div>

                            </div>

                            {{-- column right : ajax target div --}}

                            <!-- <div class="col-6 p-3">
                                <div class="border rounded text-center" style='height:100%; width:100%;'
                                    id="transfer_order_mr_details" name="transfer_order_mr_details">
                                    <label class="mt-3">Select MR num to display order details</label>
                                </div>
                            </div> -->

                        </div>
                    </div>

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

                        {{-- <form action="" method="POST" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-sm-12 form-grop">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add items</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="40%" class="table-info">Item code</th>
                                                    <th width="10%" class="table-info">Stock</th>
                                                    <th width="10%" class="table-info">Transfer QTY</th>
                                                    <th width="18%" class="table-info">Serial no</th>
                                                    <th width="16%" class="table-info">
                                                        <div class="d-flex row justify-content-between pl-2 pr-2">
                                                            Selected serials
                                                            <button class="btn btn-sm btn-info"
                                                                onclick="clearSerialList()">
                                                                <span class="fas fa-eraser">
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="form-control chosen-select" id="item"
                                                            name="item" onchange="onItemSelect()">
                                                            <option value="" disabled selected hidden>Select
                                                                item
                                                            </option>
                                                            @foreach ($itemlist as $i)
                                                                <option value="{{ $i->id }}">
                                                                    {{ $i->item_no }} {{ $i->description }}</option>
                                                            @endforeach
                                                        </select>

                                                        {{-- hidden --}}
                                                        <select hidden class="form-control chosen-select" id="item_type"
                                                            name="item">
                                                            <option value="" disabled selected hidden>Select
                                                                item type
                                                            </option>
                                                            @foreach ($itemlist as $i)
                                                                <option value="{{ $i->id }}">
                                                                    {{ $i->item_type_id }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                   
                                                    <td>
                                                        <input type="hidden" id="item_stock_id" />
                                                        <input
                                                            style="appearance:none; border:none; background-color: transparent;"
                                                            type="number" class="form-control" id="item_stock"
                                                            readonly="true" />
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="item_transfer_qty"
                                                            min="0" oninput="this.value = Math.abs(this.value)" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            id="item_transfer_serial_check" />
                                                        <div class="row m-1 mt-3 d-flex justify-content-between">
                                                            <button id="verify_serial"
                                                                class="btn btn-dark btn-sm text-bold" style="width:100%"
                                                                onclick="verifySerial()">VERIFY</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <textarea id="item_transfer_selected_serials" style="resize:none; height:100%;" rows="4" disabled></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" style="text-align:right"><button type="button"
                                                            class="btn btn-info btn-sm text-bold"
                                                            onclick="append_tr()"><span
                                                                class="fas fa-plus mr-2"></span>ADD</button></td>
                                                </tr>

                                            </tbody>


                                        </table>

                                        <table id="transfer_order_item_table" class="table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="table-success">Item code</th>
                                                    <th class="table-success">Stock</th>
                                                    <th class="table-success">Request Qty</th>
                                                    <th class="table-success">Transfered Qty</th>
                                                    <th class="table-success text-center">QTY</th>
                                                    <th class="table-success text-center">Selected serials</th>
                                                    <th class="table-success text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="transfer_order_item_table_body">
                                            </tbody>
                                            {{-- This one tracks the row count --}}
                                            <input type="hidden" name="row_count" id="row_count" value="0">
                                        </table>
                                    </div>
                                </div>

                                {{-- </form> --}}

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="d-flex justify-content-end mr-3">
                            <button type="submit" class="btn btn-success" onclick="onTransferOrderSubmit()">Submit
                                transfer
                                order</button>
                        </div>
                        <!-- /.card-footer -->
            </section>

        </div>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
          
           
            var type = $('#transfer_order_type').val();
            if(type = 1){
                onMRSelect();
            }
            //  $(".chosen-select").chosen({rtl: true});

            $('#transfer_order_mr').select2();
            $('#transfer_order_from').select2();
            $('#transfer_order_to').select2();

            $('#item').select2();
        });


        // called when selecting the type of the transfer order
        // ---------------------------------------------------

        function onTransferOrderTypeSelect() {

            var type = $('#transfer_order_type').val();
            if (type == 2) {
                $('#transfer_order_mr').prop('disabled', false);
                $('#transfer_order_to').prop('disabled', true);
                $("#transfer_order_to").val('').trigger("change");
                $('#transfer_order_mr_details').html(
                    "<label class='mt-3'>Select MR num to display order details</label>"
                );
            } else if (type == 1) {
                $('#transfer_order_mr').prop('disabled', true);
                $('#transfer_order_to').prop('disabled', false);
                $("#transfer_order_mr").val('').trigger("change");
                $('#transfer_order_mr_details').html(
                    "<label class='mt-3'>Transfer type direct. No details available</label>"
                );
            }

        }


        // called when changing the value of the MR order selector
        // -------------------------------------------------------

        function onMRSelect() {

var MR = $('#transfer_order_mr').val();

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
        var row_count = $('#row_count').val();
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


        // called when changing the value of the source warehouse selector
        // --------------------------------------------------------------

        function onSourceLocationSelect(source_location) {

            var item = $('#item').val();

            if (item != null) {
                onItemSelect();
            }

            $('#transfer_order_item_table tbody').empty();
        }

        // called when changing the value of the target warehouse selector
        // --------------------------------------------------------------

        function onTargetLocationSelect(target_location) {

        }


        // called when changing the value of the item selector
        // ---------------------------------------------------

        function onItemSelect() {

            var select_item_type = document.getElementById('item_type');

            var location = $('#transfer_order_from').val();
            var item = $('#item').val();

            $('#item_type').val(item);
            // $('#item_description').val(item);
            $('#item_transfer_qty').val(0);
            $('#item_transfer_serial_check').val('');
            $('#item_transfer_selected_serials').val('');

            var item_type = parseInt(select_item_type.options[select_item_type.selectedIndex].text);

            // if item type is of 0, disable the serial selectors, and the verify button

            if (item_type == 0) {
                $('#item_transfer_serial_check').val('').prop('disabled', true);
                $('#verify_serial').prop('disabled', true);
            } else if (item_type == 1) {
                $('#item_transfer_serial_check').val('').prop('disabled', false);
                $('#verify_serial').prop('disabled', false);
            }


            $.ajax({
                url: "{{ url('stock/for_location') }}",
                method: "GET",
                data: {
                    "item": item,
                    "location": location,
                },
                success: function(data) {
                    $('#item_stock_id').val(data.stock_id);
                    $('#item_stock').val(data.stock_qty);
                    $('#item_transfer_qty').prop('max', data.stock_qty);
                },
            });

        }


        // called on pressing the eraser button on the Serial List table head
        // ------------------------------------------------------------------
        function clearSerialList() {
            Swal.fire({
                icon: "question",
                title: "Clear all selected serials?",
                showDenyButton: true,
                confirmButtonText: "Yes",
                denyButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#item_transfer_selected_serials').val('');
                }
            })

        }

        // called on pressing the VERIFY button for serials
        // ------------------------------------------------

        function verifySerial() {

            var serialToCheck = $('#item_transfer_serial_check').val().trim();
            var transferQty = $('#item_transfer_qty').val();

            var item = $('#item').val();
            var stock = $('#item_stock_id').val();
            var fromlocation = $('#transfer_order_from').val();

            console.log(stock);

            // pre validations
            // check if the item is selected
            // check if the serial isn't empty

            if (item == null) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "No item selected",
                    text: "Select an item first to verify the serial",
                    showConfirmButton: 1,
                });
            } else if (serialToCheck == '') {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Serial empty",
                    text: "Please fill in a serial number to verify",
                    showConfirmButton: 1,
                });
            } else {
                $.ajax({
                    url: "{{ url('serial/for_stock') }}",
                    method: "GET",
                    data: {
                        'item': item,
                        'stock': stock,
                        'fromlocation':fromlocation
                    },
                    success: function(data) {

                        // booleans to track serial presence
                        var serialIsThereForStock = false;
                        var serialIsThereForItem = false;
                        var serialIsSomewhereElse = false;
                        var serialIsForSomethingElse = false;

                        var serialListForAll = data.serialListForAll.map(serial => serial
                            .serial_no);
                        var serialListForCurrentItem = data.serialListForCurrentItem.map(serial => serial
                            .serial_no);
                        var serialListForCurrentStock = data.serialListForCurrentStock.map(serial => serial
                            .serial_no);

                        serialIsThereForStock = serialListForCurrentStock.includes(serialToCheck);
                        serialIsThereForItem = serialListForCurrentItem.includes(serialToCheck);
                        serialIsSomewhereElse = (serialIsThereForItem && !serialIsThereForStock);
                        serialIsForSomethingElse = (!serialIsThereForItem && serialListForAll.includes(
                            serialToCheck));

                        // current stock serial list
                        console.log(serialListForCurrentStock);
                        console.log(serialListForCurrentItem);

                        // bool check 
                        console.log(serialIsThereForStock);
                        console.log(serialIsThereForItem);
                        console.log(serialIsSomewhereElse);
                        console.log(serialIsForSomethingElse);

                        if (serialIsThereForStock) {
                            Swal.fire({
                                icon: "success",
                                type: "success",
                                title: "Serial valid",
                                text: "Add to the selected serials list?",
                                showConfirmButton: 1,
                                showDenyButton: 1
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    serialString = $('#item_transfer_selected_serials').val();
                                    serials = (serialString == '') ? [] : serialString.split(',');

                                    if (serials.length < transferQty) {
                                        if (serialString == '') {
                                            $('#item_transfer_selected_serials').val(serialToCheck);
                                        } else {
                                            if (!serials.includes(serialToCheck)) {
                                                serialString = serialString + ',' + serialToCheck;
                                                $('#item_transfer_selected_serials').val(serialString);
                                            } else {
                                                Swal.fire({
                                                    icon: "error",
                                                    type: "error",
                                                    title: "Serial already selected",
                                                    showConfirmButton: 1
                                                });
                                            }

                                        }
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            type: "error",
                                            title: "Max serials reached for quantity",
                                            showConfirmButton: 1
                                        });
                                    }
                                }
                            });
                        } else if (serialIsSomewhereElse) {

                            // if serial is somewhere else, search where it is

                            $.ajax({
                                url: "{{ url('serial/get_location') }}",
                                method: "GET",
                                data: {
                                    'serial': serialToCheck,
                                    'fromlocation': fromlocation
                                },
                                success: function(data) {

                                    var locationForSerial = data.locationForSerial;

                                    Swal.fire({
                                        icon: "warning",
                                        type: "warning",
                                        title: "Serial is not here",
                                        text: "The item with this serial is stored in " +
                                        locationForSerial,
                                        showConfirmButton: 1
                                    });
                                },
                            });

                        } else if (serialIsForSomethingElse) {

                            // if serial is for something else, search what item it's for

                            $.ajax({
                                url: "{{ url('serial/get_item') }}",
                                method: "GET",
                                data: {
                                    'serial': serialToCheck,
                                },
                                success: function(data) {
                                    
                                    var itemForSerial = data.itemForSerial;

                                    Swal.fire({
                                        icon: "error",
                                        type: "error",
                                        title: "Serial already taken",
                                        text: "This serial already belongs to item " +
                                            itemForSerial,
                                            showConfirmButton: 1
                                    });
                                },
                            });

                        } else {
                            Swal.fire({
                                icon: "error",
                                type: "error",
                                title: "Serial does not exist",
                                text: "Add it to register upon transfer order submission?",
                                showConfirmButton: 1,
                                showDenyButton: 1
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    serialString = $('#item_transfer_selected_serials').val();
                                    serials = (serialString == '') ? [] : serialString.split(',');

                                    if (serials.length < transferQty) {
                                        if (serialString == '') {
                                            $('#item_transfer_selected_serials').val(serialToCheck);
                                        } else {
                                            if (!serials.includes(serialToCheck)) {
                                                serialString = serialString + ',' + serialToCheck;
                                                $('#item_transfer_selected_serials').val(serialString);
                                            } else {
                                                Swal.fire({
                                                    icon: "error",
                                                    type: "error",
                                                    title: "Serial already selected",
                                                    showConfirmButton: 1
                                                });
                                            }

                                        }
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            type: "error",
                                            title: "Max serials reached for quantity",
                                            showConfirmButton: 1
                                        });
                                    }
                                }
                            });
                        }

                    },
                });
            }

        }


        // called on pressing the ADD button. Appends item row
        // ---------------------------------------------------

        function append_tr() {

            var select_item = document.getElementById('item');
            var select_item_type = document.getElementById('item_type');
            // var select_description = document.getElementById('item_description');

            // var item_description = select_description.options[select_description.selectedIndex].text;
            var item_type = parseInt(select_item_type.options[select_item_type.selectedIndex].text);
            var item_code = select_item.options[select_item.selectedIndex].text;
            var item_id = select_item.options[select_item.selectedIndex].value;

            var item_transfer_serials = (item_type == 1) ? $('#item_transfer_selected_serials').val() : 'N/A';
            var item_transfer_serial_count = (item_transfer_serials == '') ? [].length : item_transfer_serials.split(',')
                .length;

            var item_transfer_qty = $('#item_transfer_qty').val();
            var item_stock_capacity = $('#item_transfer_qty').prop('max');

            var exceeds_stock = (parseInt(item_transfer_qty) > parseInt(item_stock_capacity));
            var serial_qty_mismatch = (parseInt(item_transfer_qty) != item_transfer_serial_count);

            var items = [];
            $('.item').each(function() {
                items.push($(this).val());
            });


            if (item_id == "") {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "No item selected",
                    text: "Select an item to add",
                    showConfirmButton: 1
                });
            } else if (item_transfer_qty == 0) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Selected quantity is 0",
                    text: "Increase the quantity to add",
                    showConfirmButton: 1
                });
            } else if (exceeds_stock) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Not enough stock",
                    text: "Quantity should be within the range of the stock",
                    showConfirmButton: 1
                });
            } else if (serial_qty_mismatch && item_type == 1) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Missing serial numbers",
                    text: "Add serial numbers to match the quantity",
                    showConfirmButton: 1
                });
            } else if (items.includes(item_id)) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Item is already in the list",
                    showConfirmButton: 1
                });
            } else {
                var row_count = $('#row_count').val();
                $('#row_count').val(parseInt(row_count) + 1);

                $('#transfer_order_item_table_body').append('<tr id=tr_"' + row_count + '">' +
                    '<td>' + item_code + '<input class="item" type="hidden" value="' + item_id + '" id="item_id_' +
                    row_count +
                    '" name="item_id_' + row_count + '" /> <input class="item_type" type="hidden" value="' + item_type +
                    '" id="item_type_' + row_count + '" /></td>' +
                    '<td style="text-align:center">-</td>'+ 
        '<td style="text-align:center">-</td>'+ 
        '<td style="text-align:center">-</td>'+ 
                    // '<td>' + item_description + '<input type="hidden" value="' + item_description +
                    // '" id="item_description_' + row_count + '" name="item_description_' + row_count + '" /></td>' +
                    '<td style="text-align:center">' + item_transfer_qty +
                    '<input class="qty" type="hidden" value="' +
                    item_transfer_qty + '" id="qty_' + row_count + '" name="qty_' + row_count + '" /></td>' +
                    '<td style="text-align:center">' + item_transfer_serials +
                    '<input class="serials" type="hidden" value="' +
                    item_transfer_serials + '" id="serials_' + row_count + '" name="serials_' + row_count +
                    '" /></td>' +
                    '<td style="text-align:center"><button type="button" class="btn btn-danger btn-sm" onclick="delete_row(this)"><span class="fas fa-eraser"></span></button></td>' +
                    '</tr>');
            }

        }

        function delete_row(btn) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);

            var row_count = $('#row_count').val();
            $('#row_count').val(parseInt(row_count) - 1);
        }


        function onTransferOrderSubmit() {

            var type = $('#transfer_order_type').val();
            var MR = $('#transfer_order_mr').val();
            var from = $('#transfer_order_from').val();
            var to = $('#transfer_order_to').val();
            var row_count = $('#row_count').val();
            var remark = $('#remark').val();
            var helpdeskno = $('#helpdeskno').val();

            // validations : seperate

            if (type == null) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "Transfer order type not selected",
                    text: "Please select your transfer order type before submitting!",
                    showConfirmButton: 1
                });
            } else if (MR == null && type == 2) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "MR number not selected",
                    text: "The MR number is required for MR transfers",
                    showConfirmButton: 1
                });
            } else if (from == null) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "Source location not selected",
                    text: "Please select your item source location before submitting!",
                    showConfirmButton: 1
                });
            } else if (to == null) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "Target location not selected",
                    text: "Please select your item target location before submitting!",
                    showConfirmButton: 1
                });
            } else if (from == to) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "Same source and target",
                    text: "Please select different warehouses for source and target locations before submitting!",
                    showConfirmButton: 1
                });
            } else if (row_count == 0) {
                Swal.fire({
                    icon: "warning",
                    type: "error",
                    title: "No items",
                    text: "Add the required items before submitting!",
                    showConfirmButton: 1
                });
            } else {
                // if all is valid
                // confirmation
                Swal.fire({
                    icon: 'question',
                    title: 'Confirm transfer order submission ?',
                    showDenyButton: true,
                    confirmButtonText: 'Submit',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        var items = [];
                        var item_types = [];
                        var qtys = [];
                        var serials = [];

                        $('.item').each(function() {
                            items.push($(this).val());
                        })

                        $('.item_type').each(function() {
                            item_types.push($(this).val());
                        })

                        $('.qty').each(function() {
                            qtys.push($(this).val());
                        })

                        $('.serials').each(function() {
                            serials.push($(this).val());
                        })


                        // ajax setup
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });


                        $.ajax({
                            url: "{{ url('transfer_order/create') }}",
                            method: "POST",
                            data: {
                                'from': from,
                                'to': to,
                                'type': type,
                                'items': items,
                                'item_types': item_types,
                                'qtys': qtys,
                                'serials': serials,
                                'mr': MR,
                                'remark': remark,
                                'helpdeskno': helpdeskno,
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
                });


            }
        }


        function change_order_status(orderid, storestatus, center_status) {

            var referenceno = '';
            if (storestatus == 1) {
                console.log(storestatus);
                referenceno = $('#referenceno_' + orderid).val();
                $("#referenceno_" + orderid).css("border", "1px solid red");
                $("#referenceno_" + orderid).focus();

            } else {
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
