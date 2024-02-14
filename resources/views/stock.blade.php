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
                            <li class="breadcrumb-item active">Stock Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <!-- general form elements disabled -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Stock Report</h3>

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
                                    <h3 class="card-title">Stock Report</h3> 
                                    <div style="float: right;">
                                    <!-- <button type="button" class="btn btn-outline-danger btn-sm" >Export as PDF</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" >Export as Excel</button> -->
                                </div>
                                </div>
                                <div class="card-body">
                                <form action="{{ route('stock') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <!-- <div class="col-sm-3">

                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" id="from" name="from" class="form-control"  @if(isset($from)) value="{{$from}}" @else value="{{date('Y-m-d')}}" @endif></input>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>To </label>
                                        <input type="date" id="to" name="to" class="form-control" @if(isset($to)) value="{{$to}}" @else value="{{date('Y-m-d')}}" @endif></input>
                                    </div>
                                </div> -->
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>Item</label>
                                        <select class="form-control chosen-select" name="item" id="item">
                                            <option value="">
                                                <-- Select --->
                                            </option>
                                            @foreach ($itemlist as $item)
                                                <option value="{{ $item->id }}" @if($item_id == $item->id) selected="selected" @endif> {{ $item->item_no }} -  {{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                        <label>Location</label>
                                        <select class="form-control" name="location" id="location">
                                            <option value="">
                                                <-- Select --->
                                            </option>
                                            @foreach ($locationlist as $location)
                                                <option value="{{ $location->id }}" @if($location_id == $location->id) selected="selected" @endif> {{ $location->code }} - {{ $location->location }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                    <button class="btn btn-success savebtn" type="submit">Search</button>
                                    </div>
                                </div>
                                
                            </div>

                            <!-- <div class="form-group">
                                                <input type="reset" name="reset" value="Reset" class="btn btn-dark">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div> -->
                    </div>
                    </form>
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th >Location Code</th>
                                                    <th>Location</th>
                                                    <th>Category</th>
                                                    <th>Item Code</th>
                                                    <th>Item</th>
                                                    <th>Item Type</th>
                                                    <th>Quantity</th>
                                                    <th>Serial Numbers</th>
                                                    <!-- <th>Balance</th> -->
                                                    <!-- <th>Status</th>
                                                    <th hidden>Created</th>
                                                    <th hidden>Updated</th> -->
                                                    <!-- <th Colspan="2">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stocklist as $sl)
                                                <tr>
                                                    
                                                    @if(isset($sl['location']))
                                                    <td >{{$sl['location']['code']}}</td>
                                                    <td><a>{{$sl['location']['location']}}</a></td>
                                                    @else
                                                    <td></td>
                                                    <td></td>
                                                    @endif
                                                    <td><a>{{$sl['item']['category']}}</a></td>
                                                    <td>{{$sl['item']['item_no']}}</td>
                                                    <td>{{$sl['item']['description']}}</td>
                                                    <td>{{$sl['item']['item_type']}}</td>
                                                    <td>
                                                        <a href="#" class="link-info" data-toggle="modal" data-target="#exampleModalLong" onclick="set_data(<?php echo $sl['item']['id'] ?>);">
                                                            <?php
                                                                $qty = $sl->qty;
                                                                if ($qty != 0) {
                                                                    echo $qty;
                                                                }
                                                            ?>
                                                        </a>
                                                    </td>
                                                    
                                                    <!-- <td>{{$sl->balance}}</td> -->
                                                    <!-- <td>
                                                        @if($sl->status == 1)
                                                        <span class="badge badge-success">ACTIVE</span>
                                                        @else
                                                        <span class="badge badge-danger">BLOCK</span>
                                                        @endif
                                                    </td>
                                                    <td hidden>{{$sl->created_at}}</td>
                                                    <td hidden>{{$sl->updated_at}}</td> -->
                                                    <!-- <td>
                                                        <div class="btn btn-default btn-sm btn-flat">
                                                            Edit
                                                        </div>
                                                    </td> -->
                                                    <td>
                                                        @if(count($sl->serials) > 0)
                                                            <ul>
                                                                @foreach($sl->serials as $serial)
                                                                    @if($serial->serial_no !== null)
                                                                        <li>{{ $serial->serial_no }}</li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
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

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Item Movement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <table class="table table-bordered">
        <thead>
            <td>Type</td>
            <td>QTY</td>
        </thead>
       <tbody id="item_body">
        </tbody>
        <tbody id="t_footer">
        </tbody>
       </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

{{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
<script>
 $(document).ready(function() {
            $('#location').select2();
            $('#item').select2();
        });

$('#exampleModalLong').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
})


    function set_data(item_id, location_id){
        $("#item_body").remove();

    $.ajax({
        url: "{{ url('/stock_movement_details') }}",
        method: "GET",
        data: {
            "item_id": item_id,
            "location_id": location_id,
        },
        success: function(data) {
            console.log(data.stock_history);
            var sum_qty = 0;
            if(data.stock_history.length > 0){
                for(var i=0; i< data.stock_history.length; i++){
                    sum_qty = sum_qty + data.stock_history[i]['qty'];
                    $('#item_body').append('<tr>' +
                    '<td style="text-align:center">'+ data.stock_history[i]['type'] +'</td>' +
                    '<td style="text-align:center">'+ data.stock_history[i]['qty'] +'</td>' +
                    '</tr>');
                }
                $('#t_footer').append('<tr>' +
                    '<td style="text-align:center">Available</td>' +
                    '<td style="text-align:center">'+ sum_qty +'</td>' +
                    '</tr>');
               
            }
           
        },
    });



}
</script>
@endsection
