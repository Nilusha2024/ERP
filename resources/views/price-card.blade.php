{{-- price card interface --}}
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
                                <li class="breadcrumb-item active">Price Card</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                <!-- general form elements disabled -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Price cards</h3>

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

                            {{-- column left --}}
                            <div class="col-sm-7 border-right pr-3 pt-1">

                                <label class="h4 pl-1 pt-1 pb-3">Add items</label>

                                <table class="table table-striped table-bordered ">
                                    <tbody>
                                        <tr>
                                            <th width="20%" class="table-secondary">Item Code</th>
                                            <td>
                                                <select id="item" class="form-control form-control-chosen"
                                                    name="item" onchange="onItemSelect()">
                                                    <option value="" disabled selected hidden>
                                                        Select an item
                                                    </option>
                                                    @foreach ($itemlist as $i)
                                                        <option value="{{ $i->id }}">{{ $i->item_no }} {{ $i->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="table-secondary">Description</th>
                                            <td>
                                                <select style="appearance:none; border:none; background-color: transparent;"
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
                                        </tr>
                                        <tr>
                                            <th width="20%" class="table-secondary">Item type</th>
                                            <td>
                                                <select id="item_type" class="form-control form-control-chosen"
                                                    name="item_type">
                                                    <option value="" disabled selected hidden>
                                                        Select an item type
                                                    </option>
                                                    <option value=1>New</option>
                                                    <option value=2>Used</option>
                                                    <option value=3>Discarded</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="table-secondary">Price</th>
                                            <td>
                                                <input type="number" value="0" class="form-control" id="item_price"
                                                    min="0"  step="any"/>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="text-align:right"><button type="button"
                                                    class="btn btn-secondary btn-sm text-bold" onclick="append_tr()"><span
                                                        class="fas fa-plus mr-2"></span>ADD</button></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            {{-- column right --}}
                            <div class="col-5 p-3">
                                <div>
                                    <div class="border rounded text-center" style='height:350px; width:100%;'
                                        id="price_card_details_holder" name="price_card_details_holder">
                                        <label class="mt-3">Price list</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->

                        <div class="p-2"></div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Price cards to be submitted</h3>
                            </div>
                            <div class="card-body">
                                <table id="price_card_item_table" class="table table-striped table-bordered"
                                    style="width:100%">

                                    <head>
                                        <tr>
                                            <th class="table-success">Item code</th>
                                            <th class="table-success">Description</th>
                                            <th class="table-success">Item type</th>
                                            <th class="table-success text-center">Price</th>
                                            <th class="table-success text-center">Action</th>
                                        </tr>
                                    </head>
                                    <tbody id="price_card_item_table_body">
                                    </tbody>
                                    <input type="hidden" name="row_count" id="row_count" value="0">
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="d-flex justify-content-end mr-3">
                            <button type="submit" class="btn btn-success" onclick="onPriceCardSubmit()">Submit price
                                cards</button>
                        </div>
                    </div>
            </section>
        </div>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#item').select2();
            $('#item_type').select2();
        });


        // called when changing the value of the item selector
        // ---------------------------------------------------

        function onItemSelect() {

            let select_item = document.getElementById('item');

            let item_id = select_item.options[select_item.selectedIndex].value;
            let item_code = select_item.options[select_item.selectedIndex].text;

            $('#item_description').val(item_id);
            $('#item_price').val(0);
            $('#item_selected_prices').val('');

            if (item != null) {
                $.ajax({
                    url: "{{ url('price_card/details') }}",
                    method: "GET",
                    data: {
                        "item_id": item_id,
                        "item_code": item_code,
                    },
                    success: function(data) {
                        $('#price_card_details_holder').html(data);
                    },
                });
            }



        }


        // called on pressing the ADD button. Appends item row
        // ---------------------------------------------------


        function append_tr() {

            let select_item = document.getElementById('item');
            let select_description = document.getElementById('item_description');
            let select_item_type = document.getElementById('item_type');

            let item_id = select_item.options[select_item.selectedIndex].value;
            let item_code = select_item.options[select_item.selectedIndex].text;
            let item_description = select_description.options[select_description.selectedIndex].text;

            let item_type = select_item_type.options[select_item_type.selectedIndex].value;
            let item_type_label = select_item_type.options[select_item_type.selectedIndex].text;

            let item_price = parseFloat($('#item_price').val()).toFixed(2);

            let items = [];
            let current_prices = [];

            $('.item').each(function() {
                items.push($(this).val());
            });

            $('.current_price').each(function() {
                current_prices.push($(this).val());
            });


            if (item_id == "") {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "No item selected",
                    text: "Select an item to add",
                    showConfirmButton: 1
                });
            } else if (item_type == "") {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Item type not selected",
                    text: "Select an item type",
                    showConfirmButton: 1
                });
            } else if (item_price == 0) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Price not set",
                    text: "Set a price for the item",
                    showConfirmButton: 1
                });
            } else if (current_prices.includes(item_price)) {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: "Price exists for item",
                    text: "Set a different price to add",
                    showConfirmButton: 1
                });
            } else {

                let row_count = $('#row_count').val();
                $('#row_count').val(parseInt(row_count) + 1);

                $('#price_card_item_table_body').append('<tr id=tr_"' + row_count + '">' +
                    '<td>' + item_code + '<input class="item" type="hidden" value="' + item_id + '" id="item_id_' +
                    row_count +
                    '" name="item_id_' + row_count + '" /></td>' +
                    '<td>' + item_description + '<input type="hidden" value="' + item_description +
                    '" id="item_description_' + row_count + '" name="item_description_' + row_count + '" /></td>' +
                    '<td>' + item_type_label + '<input class="item_type" type="hidden" value="' + item_type +
                    '" id="item_type_' +
                    row_count +
                    '" name="item_type_' + row_count + '" /></td>' +
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


        function onPriceCardSubmit() {

            let row_count = $('#row_count').val();

            // validations : seperate

            if (row_count == 0) {
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
                    title: 'Confirm price card submission ?',
                    showDenyButton: true,
                    confirmButtonText: 'Submit',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        let items = [];
                        let item_types = [];
                        let prices = [];

                        $('.item').each(function() {
                            items.push($(this).val());
                        })

                        $('.item_type').each(function() {
                            item_types.push($(this).val());
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
                            url: "{{ url('price_card/create') }}",
                            method: "POST",
                            data: {
                                'items': items,
                                'item_types': item_types,
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


        // called on pressing the eraser button on the Serial List table head
        // ------------------------------------------------------------------
        // function clearPriceList() {
        //     Swal.fire({
        //         icon: "question",
        //         title: "Clear all selected prices?",
        //         showDenyButton: true,
        //         confirmButtonText: "Yes",
        //         denyButtonText: "No",
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $('#item_selected_prices').val('');
        //         }
        //     })

        // }


        // called on pressing the add price button
        // ---------------------------------------

        // function addPrice() {

        //     item = $('#item').val();

        //     priceToAdd = $('#item_price').val();
        //     priceString = $('#item_selected_prices').val();
        //     prices = (priceString == '') ? [] : priceString.split(',');

        //     if (item == null) {
        //         Swal.fire({
        //             icon: "error",
        //             type: "error",
        //             title: "No item selected",
        //             text: "Select an item first to add prices",
        //             showConfirmButton: 1,
        //         });
        //     } else if (priceToAdd <= 0) {
        //         Swal.fire({
        //             icon: "error",
        //             type: "error",
        //             title: "Selected price is 0",
        //             text: "Set a proper price to add",
        //             showConfirmButton: 1,
        //         });
        //     } else {
        //         if (priceString == '') {
        //             $('#item_selected_prices').val(priceToAdd);
        //         } else {
        //             if (!prices.includes(priceToAdd)) {
        //                 priceString = priceString + ',' + priceToAdd;
        //                 $('#item_selected_prices').val(priceString);
        //             } else {
        //                 Swal.fire({
        //                     icon: "error",
        //                     type: "error",
        //                     title: "Price already selected",
        //                     showConfirmButton: 1
        //                 });
        //             }

        //         }
        //     }
        // }
    </script>
@endsection
