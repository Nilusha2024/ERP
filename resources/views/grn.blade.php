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
                                <li class="breadcrumb-item active">GRN</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                <div class="card card-primary">
                    <div class="card-header">
                        <a href="{{ route('grnview') }}" class="btn btn-success btn-lg">View GRN List</a>
                    </div>
                    <div class="card-body p-4">


                    </div>
                </div>
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <!-- <div class="card-header">
                            <h3 class="card-title">GRN.</h3>

                        </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        {{-- <form action="{{ route('grn') }}" method="POST" enctype="multipart/form-data"> --}}

                        @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <!-- text input -->

                                <div class="form-group">
                                    <!-- dop down list  -->
                                    <label>PO ID</label>
                                    <select class="form-control" name="poselect" id="poselect"
                                        onchange="load_po_details();">
                                        <option value="">
                                            <-- Select --->
                                        </option>
                                        @foreach ($po as $po)
                                            <option value="{{ $po->id }}"> {{ $po->po_no }} </option>
                                        @endforeach
                                    </select>

                                    <label>Grn No</label>
                                    <input type="text" class="form-control" placeholder="GRN number" name="grnNo"
                                        id="grnNo" value="{{ $nextid }}" readonly="true">

                                    <label>PO Status</label>
                                    <select class="form-control" name="postatus" id="postatus">
                                        <option value="0">Partially GRN</option>
                                        <option value="1">Fully GRN</option>
                                    </select>
                                    <label>Supply Invoice</label>
                                    <input type="text" class="form-control" placeholder="Reference Number" name="ref_no"
                                        id="ref_no">

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">GRN Table</h3>
                                </div>

                                <div class="card-body">
                                    <table id="" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Description</th>
                                                <th>Po Qty</th>
                                                <th>GRN Qty</th>
                                                <th>Qty</th>
                                                <th>Serial no</th>
                                                <th>Selected serials</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                    </table>
                                    <br>

                                    <div class="col-md-12 bg-light text-right">
                                        <button class="btn btn-primary btn-lg" onclick="stockIn()">Stock In</button>
                                    </div>
                                    <input type="hidden" id="row_count" name="row_count" value="0" />
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </form> --}}
                    <!-- /.card-body -->
                </div>
            </section>

        </div>
    </div>

    {{-- imports --}}
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            document.getElementById("poselect").focus();
            //  $(".chosen-select").chosen({rtl: true});

            $('#poselect').select2();
        });



        ////Tab Focuse
        document.getElementById("poselect").addEventListener("click", () => {
            document.getElementById("qty_0").focus();
        });

        function load_po_details() {

            var id = $('#poselect').val();
            $('#row_count').val(0);
            $.ajax({
                url: "{{ url('getpodetals') }}",
                method: "GET",
                data: {
                    "id": id
                },
                success: function(data) {

                    $("#tbody").empty();
                    $('#row_count').val(data.data.length);

                    for (var x = 0; x <= data.data.length; x++) {
                        var y = x + 1;
                        var qtydif = data.data[x]['pod_qty'] - data.data[x]['grn_qty'];
                        $('#tbody').append('<tr id=tr_"' + x + '">' +
                            '<td>' + data.data[x]['item_no'] + '</td>' +
                            '<td>' + data.data[x]['description'] + '</td>' +
                            '<td>' + data.data[x]['pod_qty'] +
                            '<input type="hidden" class="item" value="' + data.data[x]['item_id'] +
                            '" id="item_id_' + x + '" name="item_id_' + x +
                            '" /><input type="hidden" class="mr" value="' + data.data[x]['mr_status'] +
                            '" id="mr_status_' + x + '" name="mr_status_' + x + '" /></td>' +
                            '<td><input type="text" class="form-control" readonly="true" value="' + data
                            .data[x]['grn_qty'] + '" /></td>' +
                            '<td style="text-align:right"><input type="text" class="form-control qty" value="' +
                            qtydif + '" id="qty_' + x + '" name="qty_' + x + '"/> ' +
                            '<input type="hidden" value="' + data.data[x]['stock_id'] +
                            '" id="item_stock_id_' + x + '" name="item_stock_id_' + x + '" /></td>' +
                            '</td>' +
                            '<td style="text-align:right"><input type="text" class="form-control" id="serial_to_check_' +
                            x +
                            '" name="serial_' + x + '" class="form-control"/>' +
                            '<div class="row m-1 mt-3 d-flex justify-content-between"> <button onclick="verifySerial(' +
                            x +
                            ')" class="btn btn-primary btn-sm text-bold" style="width:100%"> VERIFY </button> </div>' +
                            '</td>' +

                            '<td><textarea class="serials" id="grn_selected_serials_' + x +
                            '" style="resize:none; height:100%;" rows="4" disabled></textarea></td>' +
                            '</tr>');
                        if (qtydif == 0) {
                            document.getElementById("qty_" + x).readOnly = true;
                        }


                    }

                }
            });
        }


        // called when clicking stock in button

        function stockIn() {

            var grn = $('#grnNo').val();
            var ref_no = $('#ref_no').val();
            var po = $('#poselect').val();
            var postatus = $('#postatus').val();
            var row_count = $('#row_count').val();

            var items = [];
            var qtys = [];
            var serials = [];
            var mrs = [];

            $('.item').each(function() {
                items.push($(this).val());
            });

            $('.qty').each(function() {
                qtys.push($(this).val());
            });

            $('.serials').each(function() {
                serials.push($(this).val());
            });

            $('.mr').each(function() {
                mrs.push($(this).val());
            });

            // alert(serials.length);

            // ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ url('grn') }}",
                method: "POST",
                data: {
                    'grn': grn,
                    'ref_no': ref_no,
                    'po': po,
                    'items': items,
                    'qtys': qtys,
                    'serials': serials,
                    'postatus': postatus
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
                }
            });
        }

        // }



        function verifySerial(index) {

            console.log(index);

            var serialToCheck = $('#serial_to_check_' + index).val();
            var grnQty = $('#qty_' + index).val();

            var item = $('#item_id_' + index).val();
            var stock = $('#item_stock_id_' + index).val();


            // pre validations
            // check if the item is selected
            // check if the serial isn't empty
            // Check if the entered serial is equal to any other entered serials
            var duplicateSerial = false;
            $('.serials').not('#grn_selected_serials_' + index).each(function() {
                var otherSerials = $(this).val().split(',');
                if (otherSerials.includes(serialToCheck)) {
                    duplicateSerial = true;
                    return false; // exit the loop early if duplicate is found
                }
            });

            if (duplicateSerial) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Duplicate Serial",
                    text: "This serial is already entered in another row. Please enter a unique serial.",
                    showConfirmButton: 1,
                });
            } else {
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

                            if (serialIsThereForStock) {
                                Swal.fire({
                                    icon: "success",
                                    type: "success",
                                    title: "Serial valid",
                                    text: "Do you want to add this serial to the selected serials list?",
                                    showConfirmButton: 1,
                                    showDenyButton: 1
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        serialString = $('#grn_selected_serials_' + index).val();
                                        serials = (serialString == '') ? [] : serialString.split(',');

                                        if (serials.length < grnQty) {
                                            if (serialString == '') {
                                                $('#grn_selected_serials_' + index).val(serialToCheck);
                                            } else {
                                                if (!serials.includes(serialToCheck)) {
                                                    serialString = serialString + ',' + serialToCheck;
                                                    $('#grn_selected_serials_' + index).val(
                                                        serialString);
                                                } else {
                                                    Swal.fire({
                                                        icon: "error",
                                                        type: "error",
                                                        title: "Serial already selected",
                                                        text: "This serial is already in the selected serial list",
                                                        showConfirmButton: 1
                                                    });
                                                }

                                            }
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                type: "error",
                                                title: "Max serials reached for quantity",
                                                text: "The number of serials you include in the list can't exceed the GRN quantity",
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
                                    },
                                    success: function(data) {

                                        var locationForSerial = data.locationForSerial;

                                        Swal.fire({
                                            icon: "warning",
                                            type: "warning",
                                            title: "Serial is not in this source location",
                                            text: "Oops. Looks like the item with this serial is stored in " +
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
                                    text: "Would you like to add it to register upon GRN order submission?",
                                    showConfirmButton: 1,
                                    showDenyButton: 1
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        serialString = $('#grn_selected_serials_' + index).val();
                                        serials = (serialString == '') ? [] : serialString.split(',');

                                        if (serials.length < grnQty) {
                                            if (serialString == '') {
                                                $('#grn_selected_serials_' + index).val(serialToCheck);
                                            } else {
                                                if (!serials.includes(serialToCheck)) {
                                                    serialString = serialString + ',' + serialToCheck;
                                                    $('#grn_selected_serials_' + index).val(
                                                        serialString);
                                                } else {
                                                    Swal.fire({
                                                        icon: "error",
                                                        type: "error",
                                                        title: "Serial already selected",
                                                        text: "This serial is already in the selected serial list",
                                                        showConfirmButton: 1
                                                    });
                                                }

                                            }
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                type: "error",
                                                title: "Max serials reached for quantity",
                                                text: "The number of serials you include in the list can't exceed the GRN quantity",
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



        }
    </script>
@endsection
