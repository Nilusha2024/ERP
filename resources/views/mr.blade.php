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
                                <li class="breadcrumb-item active">MR</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                {{-- view card --}}
                <div class="card card-primary">
                    <div class="card-header">
                    <a href="{{ route('mrview') }}" class="btn btn-success btn-lg">View MR List</a>
                    </div>
                    <!-- <div class="card-body p-4">
                        
                        <a href="{{ route('mrview') }}" class="btn btn-success btn-lg">View MR List</a>
                    </div> -->
                </div>

                

                <div class="mt-4 mb-4">
                    <hr>
                </div>


                <!-- general form elements disabled -->
                <div class="card card-warning">
                    <!-- <div class="card-header">
                        <h3 class="card-title">MR Table</h3>

                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    
                                </ul>
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        {{-- <form action="{{ route('itemstore') }}" method="POST" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}
                        <div class="row">
                            
                                </div>


                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add items</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered">

                                            <head>
                                                <tr>
                                                    <th width="40%" class="table-warning">Item Code</th>
                                                    <th class="table-warning">Description</th>
                                                    <th class="table-warning"> QTY</th>
                                                </tr>
                                            </head>
                                            <tbody>
                                                <tr>

                                                    <td>
                                                        <select id="item" class="form-control form-control-chosen"
                                                            name="item" onchange="onItemSelect()">
                                                            <option value="">
                                                                Select an item
                                                            </option>
                                                            @foreach ($itemlist as $i)
                                                                <option value="{{ $i->id }}">{{ $i->item_no }} - {{ $i->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select
                                                            style="appearance:none; border:none; background-color: transparent;"
                                                            class="form-control chosen-select" id="item_description"
                                                            name="item_description" disabled>
                                                            <option value="" disabled selected hidden>
                                                            </option>
                                                            @foreach ($itemlist as $i)
                                                                <option value="{{ $i->id }}">
                                                                    {{ $i->description }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" value="0" class="form-control"
                                                            id="item_qty" min="0"
                                                            oninput="this.value = Math.abs(this.value)" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3" style="text-align:right"><button type="button"
                                                            class="btn btn-warning btn-sm text-bold"
                                                            onclick="append_tr()"><span
                                                                class="fas fa-plus mr-2"></span>ADD</button></td>
                                                </tr>

                                            </tbody>


                                        </table>

                                        <table id="po_item_table" class="table table-striped table-bordered"
                                            style="width:100%">

                                            <head>
                                                <tr>
                                                    <th class="table-success">ID</th>
                                                    <th class="table-success">Item code</th>
                                                    <th class="table-success">Description</th>
                                                    <th class="table-success text-center">QTY</th>
                                                    <th class="table-success text-center">Action</th>
                                                    
                                                </tr>
                                            </head>
                                            <tbody id="po_item_table_body">
                                            </tbody>
                                            <input type="hidden" name="row_count" id="row_count" value="0">
                                        </table>
                                    </div>
                                </div>

                                {{-- </form> --}}

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="d-flex justify-content-end mr-3">
                            <button type="submit" class="btn btn-success" onclick="MrSubmit()">Submit MR</button>
                        </div>
                    </div>
            </section>
        </div>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // $(".chosen-select").chosen({ rtl: true });
            $('#item').select2();
            // $('#po_vendor').select2();
        });


        // called when changing the value of the item selector
        // ---------------------------------------------------

        function onItemSelect() {

            var item = $('#item').val();

            $('#item_description').val(item);
            $('#item_qty').val(0);

        }

        function get_item_details() {
            $.ajax({
                url: "{{ url('order/get_item_details') }}",
                method: "GET",
                success: function(data) {

                },
            });
        }

        // called on pressing the ADD button. Appends item row
        // ---------------------------------------------------

        function append_tr() {

            var select_item = document.getElementById('item');
            var select_description = document.getElementById('item_description');

            var item_id = select_item.options[select_item.selectedIndex].value;
            var item_code = select_item.options[select_item.selectedIndex].text;
            var item_description = select_description.options[select_description.selectedIndex].text;

            var item_po_qty = $('#item_qty').val();

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
            } else if (item_po_qty == 0) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Selected quantity is 0",
                    text: "Increase the item quantity to add the item into the purchase order",
                    showConfirmButton: 1
                });
            } else if (items.includes(item_id)) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Can't add this item",
                    text: "Looks like this item is already in the list!",
                    showConfirmButton: 1
                });
            } else {

                var row_count = $('#row_count').val();

                $('#row_count').val(parseInt(row_count) + 1);
                
                $('#po_item_table_body').append('<tr id=tr_"' + row_count + '">' + '<td> '+row_count+'</td>' +
                    '<td>' + item_code + '<input class="item" type="hidden" value="' + item_id + '" id="item_id_' +
                    row_count +
                    '" name="item_id_' + row_count + '" /></td>' +  
                    '<td>' + item_description + '<input type="hidden" value="' + item_description +
                    '" id="item_description_' + row_count + '" name="item_description_' + row_count + '" /></td>' +
                    '<td style="text-align:center">' + item_po_qty +
                    '<input class="qty" type="hidden" value="'  + 
                    item_po_qty + '" id="qty_' + row_count + '" name="qty_' + row_count + '" /></td>' +

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


        // called when submmitting the purchase order
        // ------------------------------------------

        function MrSubmit() {

            var po_no = $('#po_no').val();
            var row_count = $('#row_count').val();

            if (po_no == '') {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "PO number not given",
                    text: "Please enter your PO number before submitting!",
                    showConfirmButton: 1
                });
            } else if (row_count == 0) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "No items",
                    text: "Looks like you didn't add any items. Go back and add the required items before submitting the purchase order!",
                    showConfirmButton: 1
                });
            } else {
                // if all is valid

                Swal.fire({
                    icon: 'question',
                    title: 'Confirm MR order submission ?',
                    showDenyButton: true,
                    confirmButtonText: 'Submit',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        var items = [];
                        var qtys = [];

                        $('.item').each(function() {
                            items.push($(this).val());
                        })

                        $('.qty').each(function() {
                            qtys.push($(this).val());
                        })

                        // ajax setup
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ url('createmr') }}",
                            method: "POST",
                            data: {
                                'items': items,
                                'qtys': qtys,
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
            // if (storestatus == 1) {
            //     console.log(storestatus);
            //     referenceno = $('#referenceno_' + orderid).val();
            //     $("#referenceno_" + orderid).css("border", "1px solid red");
            //     $("#referenceno_" + orderid).focus();

            // } else {
            //     $("#referenceno_" + orderid).css("border", "1px solid green");
            //     $("#referenceno_" + orderid).focus();
            // }



            const formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('orderid', orderid);
            formData.append('storestatus', storestatus);
            formData.append('center_status', center_status);
            formData.append('referenceno', referenceno);

        }
    </script>
@endsection
