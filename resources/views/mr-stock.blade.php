{{-- MR stock page --}}

@extends('layouts.app')

@section('content')
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
                            <li class="breadcrumb-item active">MR Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 30px;color: green;"> MR Stock Items : For {{ $user['location']['location'] }} </h3>
                </div>
                <div class="card-body">
                    <table  class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <!-- <th>Location</th> -->
                                <th>Stock</th>
                                <th>Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($MRItemList as $key => $MRItem)
                                    @if ($MRItem->qty == null)
                                    <?php $key + 1 ?>
                                        <tr>
                                            <td width="20%">
                                                <input type="hidden" class="item" value="{{ $MRItem->id }}">
                                                {{ $MRItem->item_no }}
                                            </td>
                                            <td width="40%">{{ $MRItem->description }}</td>
                                            <!-- <td width="20%">{{ $MRItem->location_id }}</td> -->
                                            <td width="10%">
                                                <input type="text" value="0" class="qty form-control" 
                                                    min="0" oninput="this.value = Math.abs(this.value)" />
                                            </td>
                                            <td width="10%"><input type="hidden" value="0" class="usedqty form-control" /></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td width="20%"><input type="hidden" class="item" value="{{ $MRItem->id }}">
                                                {{ $MRItem->item_no }}</td>
                                            <td width="40%">{{ $MRItem->description }}</td>
                                            <!-- <td width="20%">{{ $MRItem->location_id }}</td> -->
                                            <td width="10%"><input type="text" id="exist_qty_{{$MRItem->id}}" readonly value="{{ $MRItem->qty }}" class="qty form-control" 
                                                    min="0" oninput="this.value = Math.abs(this.value)"  /></td>
                                            <td width="10%"><input type="text" value="0" class="usedqty form-control" id="used_qty_{{$MRItem->id}}" 
                                                    min="0" onkeyup="check_stock(<?php echo $MRItem->id ?>)"  /></td>
                                        </tr>
                                    @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mr-3">
                        <button id="submit_mr_stock" type="submit" class="btn btn-success" onclick="submitMRStock()">Submit
                            MR Stock</button>
                    </div>

                </div>
            </div>
        </section>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            let qtys = [];

            $('.qty').each(function() {
                qtys.push($(this).val());
            })

            if (qtys.length == 0) {
                $('#submit_mr_stock').prop('disabled', true);
                $('#submit_mr_stock').html('Nothing to submit');
            }
        });

        function check_stock(id){
            var exist_qty = $('#exist_qty_' + id).val();
            var used_qty = $('#used_qty_' + id).val();
            console.log(exist_qty+'-'+used_qty);
            if(parseInt(exist_qty) < parseInt(used_qty)){
                var used_qty = $('#used_qty_' + id).val(0);
                $("#exist_qty_" + id).css("border", "1px solid red");
            }

        }

        function submitMRStock() {

            // validations: if there is any to begin with

            // confirmation
            Swal.fire({
                icon: 'question',
                title: 'Confirm MR Stock submission ?',
                showDenyButton: true,
                confirmButtonText: 'Submit',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let items = [];
                    let qtys = [];
                    let usedqtys = [];

                    $('.item').each(function() {
                        items.push($(this).val());
                    })

                    $('.qty').each(function() {
                        qtys.push($(this).val());
                    })

                    $('.usedqty').each(function() {
                        usedqtys.push($(this).val());
                    })

                    // ajax setup
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });


                    $.ajax({
                        url: "{{ url('stock/mr_item_stock_create') }}",
                        method: "POST",
                        data: {
                            'items': items,
                            'qtys': qtys,
                            'usedqtys': usedqtys
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
    </script>
@endsection
