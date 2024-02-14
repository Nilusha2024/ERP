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
                                <li class="breadcrumb-item active">PO</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                {{-- view card --}}

                <div class="card card-primary">
                    <div class="card-header">
                    <a href="{{ route('view_all_po') }}" class="btn btn-success">View Purchase Order List</a>
                    </div>
                    <!-- <div class="card-body p-4">
                        <h4 class="mb-2 text-bold">Logged in as a : <span class="text-primary">
                                {{ $user['userrole']['role'] }}</span></h4>
                        <p class="mb-3">Purchase orders viewable for :
                            <span class="text-primary text-bold">ALL</span>
                        </p>
                        
                    </div> -->
                </div>

                <div class="mt-4 mb-4">
                    <hr>
                </div>


                <!-- general form elements disabled -->
                <div class="card card-warning">
                    <!-- <div class="card-header">
                        <h3 class="card-title">Purchase Order</h3>

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

                        {{-- <form action="{{ route('itemstore') }}" method="POST" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Purchase order no: </label>
                                        <input class="form-control" name="po_no" id="po_no" value="{{$nextid}}" readonly="true"/>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Vendor: </label>
                                        <select class="form-control" name="po_vendor" id="po_vendor">
                                            <option value="" disabled selected hidden>Select vendor
                                            </option>
                                            @foreach ($vendorlist as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                                    <th width="15%" class="table-warning"> QTY</th>
                                                    <th width="20%" class="table-warning"> Price</th>
                                                </tr>
                                            </head>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select id="item" class="form-control form-control-chosen"
                                                            name="item" onchange="onItemSelect()">
                                                            <option value="" disabled selected hidden>
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
                                                            id="item_po_qty" min="0"
                                                            oninput="this.value = Math.abs(this.value)" />
                                                    </td>
                                                    <td>
                                                        <select id="item_price" class="form-control form-control-chosen"
                                                            name="item_price">
                                                            <option value="" disabled selected hidden>
                                                                Select a price
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" style="text-align:right"><button type="button"
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
                                                    <th class="table-success">Item code</th>
                                                    <th class="table-success">Description</th>
                                                    <th class="table-success text-center">QTY</th>
                                                    <th class="table-success text-center">Price</th>
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
                            <button type="submit" class="btn btn-success" onclick="onPurchaseOrderSubmit()">Submit
                                purchase
                                order</button>
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
            $('#item_price').select2();
            $('#po_vendor').select2();
        });


        // called when changing the value of the item selector
        // ---------------------------------------------------

        function onItemSelect() {

            let item = $('#item').val();

            $('#item_description').val(item);
            $('#item_po_qty').val(0);
            $('#item_price').find('option').remove()

            $.ajax({
                url: "{{ url('price_card/for_item') }}",
                method: "GET",
                data: {
                    "item": item,
                },
                success: function(data) {
                    $('#item_price').append(
                        `<option value="" disabled selected hidden>Select a price</option>`);
                    data.priceCards.forEach(priceCard => {
                        $('#item_price').append(
                            `<option value="${priceCard['price']}">${priceCard['price']}</option>`
                        );
                    });
                },
            });

        }


        // called on pressing the ADD button. Appends item row
        // ---------------------------------------------------

        function append_tr() {

            let select_item = document.getElementById('item');
            let select_description = document.getElementById('item_description');
            let select_price = document.getElementById('item_price');

            let item_id = select_item.options[select_item.selectedIndex].value;
            let item_code = select_item.options[select_item.selectedIndex].text;
            let item_description = select_description.options[select_description.selectedIndex].text;
            let item_price = select_price.options[select_price.selectedIndex].value;

            let item_po_qty = $('#item_po_qty').val();

            let items = [];
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
                    text: "Increase the quantity to add",
                    showConfirmButton: 1
                });
            } else if (item_price == "") {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Price not selected",
                    text: "Select a price",
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

                let row_count = $('#row_count').val();
                $('#row_count').val(parseInt(row_count) + 1);

                $('#po_item_table_body').append('<tr id=tr_"' + row_count + '">' +
                    '<td>' + item_code + '<input class="item" type="hidden" value="' + item_id + '" id="item_id_' +
                    row_count +
                    '" name="item_id_' + row_count + '" /></td>' +
                    '<td>' + item_description + '<input type="hidden" value="' + item_description +
                    '" id="item_description_' + row_count + '" name="item_description_' + row_count + '" /></td>' +
                    '<td style="text-align:center">' + item_po_qty +
                    '<input class="qty" type="hidden" value="' +
                    item_po_qty + '" id="qty_' + row_count + '" name="qty_' + row_count + '" /></td>' +
                    '<td style="text-align:center">' + item_price +
                    '<input class="price" type="hidden" value="' +
                    item_price + '" id="price_' + row_count + '" name="price_' + row_count + '" /></td>' +
                    '<td style="text-align:center"><button type="button" class="btn btn-danger btn-sm" onclick="delete_row(this)"><span class="fas fa-eraser"></span></button></td>' +
                    '</tr>');

            }

        }

        function delete_row(btn) {
            let row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);

            let row_count = $('#row_count').val();
            $('#row_count').val(parseInt(row_count) - 1);
        }


        // called when submmitting the purchase order
        // ------------------------------------------

        function onPurchaseOrderSubmit() {

            let po_no = $('#po_no').val();
            let vendor = $('#po_vendor').val();
            let row_count = $('#row_count').val();

            if (po_no == '') {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "PO number not given",
                    text: "Please enter your PO number before submitting!",
                    showConfirmButton: 1
                });
            } else if (vendor == null) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Vendor not selected",
                    text: "Please select your vendor before submitting!",
                    showConfirmButton: 1
                });
            } else if (row_count == 0) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "No items",
                    text: "Add the required items before submitting!",
                    showConfirmButton: 1
                });
            } else {
                // if all is valid

                Swal.fire({
                    icon: 'question',
                    title: 'Confirm purchase order submission ?',
                    showDenyButton: true,
                    confirmButtonText: 'Submit',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        let items = [];
                        let qtys = [];
                        let prices = [];

                        $('.item').each(function() {
                            items.push($(this).val());
                        })

                        $('.qty').each(function() {
                            qtys.push($(this).val());
                        })

                        $('.price').each(function() {
                            prices.push($(this).val());
                        })

                        // ajax setup
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ url('po/create') }}",
                            method: "POST",
                            data: {
                                'po_no': po_no,
                                'vendor': vendor,
                                'items': items,
                                'qtys': qtys,
                                'prices': prices
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

            let referenceno = '';
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

            // $.ajax({
            //        url: "{{ url('order/change_order_status') }}",
            //        type: 'POST',
            //        headers: {
            //            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //        },
            //        processData: false,
            //        contentType: false,
            //        data: formData,
            //        success: function(result) {


            //            if (result['status'] == 1) {

            //                Swal.fire({
            //                    position: "top-end",
            //                    type: "success",
            //                    title: result['msg'],
            //                    showConfirmButton: 1
            //                }).then(function() {
            //                    location.reload();
            //                });

            //            } else {
            //                Swal.fire({
            //                    position: "top-end",
            //                    type: "error",
            //                    title: result['msg'],
            //                    showConfirmButton: 1
            //                });
            //            }

            //        },
            //        error: function(result) {
            //            Swal.fire({
            //                position: "top-end",
            //                type: "error",
            //                title: result['msg'],
            //                showConfirmButton: !1,
            //                timer: 1500
            //            });
            //        }
            //    })
        }
    </script>
@endsection
