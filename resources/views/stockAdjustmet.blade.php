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
                            <li class="breadcrumb-item active">Stock Adjustment</li>
                        </ol>
                    </div>
                </div>
            </div>
            
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 30px;color: green;"> Stock Adjustment : For {{ $user['location']['location'] }} </h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stockadjestment') }}">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Location</label>
                                <select id="location_id" name="location_id" class="form-control" onchange="this.form.submit()">
                                    @foreach($location as $location)
                                    <option value="{{$location->id}}" >{{$location->code}} - {{$location->location}}</option>
                                            <!-- @if($location->code == 'SBSTR-GPAS')
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
                                            @endif -->
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>    
                    <table id="dataTableNoPagination" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                
                                <th>Location</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock as $key => $stock)
                                <?php $key + 1 ?>
                                    <tr>
                                    <td width="20%">{{$stock['location']['location'] }}</td>
                                        <td width="20%">
                                            <input type="hidden" class="item" value="{{ $stock['item']['id'] }}">
                                            {{ $stock['item']['item_no'] }}
                                        </td>
                                        <td width="40%">{{ $stock['item']['description'] }}</td>
                                        
                                        <td width="10%">
                                            <input type="number" value="{{ $stock['qty'] }}" class="qty form-control" id="qty_{{$key+1}}"
                                                min="0" oninput="this.value = Math.abs(this.value)" />
                                        </td>
                                        <td width="10%">
                                            <button onclick="adjust_stock(<?php echo $stock['location']['id'] ?>,<?php echo $stock['item']['id'] ?>,<?php echo $key+1 ?>)">Update</button>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- <div class="d-flex justify-content-end mr-3">
                        <button id="submit_mr_stock" type="submit" class="btn btn-success" onclick="submitMRStock()">Submit
                            MR Stock</button>
                    </div> -->

                </div>
            </div>
        </section>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            
        });

        function adjust_stock(location_id,item_id,id) {

           var qty = $('#qty_'+id).val();

            // confirmation
           // ajax setup
           $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });


                    $.ajax({
                        url: "{{ url('stock/stock_adjustment') }}",
                        method: "POST",
                        data: {
                            'location_id': location_id,
                            'item_id': item_id,
                            'qty': qty
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
    </script>
@endsection
