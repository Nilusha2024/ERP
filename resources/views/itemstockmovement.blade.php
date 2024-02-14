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
                                <li class="breadcrumb-item active">Location Wise Movement Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Item Location Wise Movement Report.</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('itemMovementLocationWise') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>From</label>
                                        <input type="date" id="from" name="from" class="form-control"
                                            @if (isset($from)) value="{{ $from }}" @else value="{{ date('Y-m-d') }}" @endif></input>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                        <label>To </label>
                                        <input type="date" id="to" name="to" class="form-control"
                                            @if (isset($to)) value="{{ $to }}" @else value="{{ date('Y-m-d') }}" @endif></input>
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <div class="form-group">
                                        <label>Item</label>
                                        <select class="form-control chosen-select" name="item" id="item">
                                            @foreach ($itemlist as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item_id == $item->id) selected="selected" @endif>
                                                    {{ $item->item_no }} - {{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>Serial Number</label>
                                        <input type="text" id="serial_no" name="serial_no" class="form-control"
                                            Placeholder="Serial No"
                                            @if (isset($serial_no)) value="{{ $serial_no }}" @endif></input>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <!-- dropdown list with search -->
                                    <div class="form-group">
                                        <label>From</label>
                                        <select class="form-control chosen-select" name="from_location" id="from_location">
                                            <option value="">Select From Location</option>
                                            @foreach ($locationlist as $location)
                                                <option value="{{ $location->id }}">{{ $location->location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <label>Search</label>
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
                    <div class="row">
                        <div class="col-sm-3 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">GRN Item Movement Report - Current Stock {{ $stock[0]['qty'] }}
                                    </h3>
                                </div>

                                <div class="card-body" style="overflow-y: scroll;">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr style="background-color:gray;font-size:20px;font-color:white">
                                                <th width="10%">#</th>
                                                <th width="25%">Location</th>
                                                <th width="25%">GRN No</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">GRN QTY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $grnsum = 0; ?>
                                            @foreach ($grn as $key => $grn)
                                                <?php $grnsum += $grn->qty; ?>
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>MSGP</td>
                                                    <td>{{ $grn->grn_no }}</td>
                                                    <td>{{ $grn->created_at }}</td>
                                                    <td>{{ $grn->qty }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoter>
                                            <tr style="background-color:gray;font-size:20px;">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:right;font-size:20px;font-color:white">QTY SUM</td>
                                                <td>{{ $grnsum }}</td>
                                            </tr>
                                        </tfoter>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Item Location Wise Movement Report</h3>
                                </div>


                                <div class="card-body" style="overflow-y: scroll; ">
                                    <table class="table table-striped table-bordered" id="dataTable">
                                        <thead>
                                            <tr style="background-color:gray;font-size:20px;font-color:white">
                                                <th width="10%">#</th>
                                                <th width="25%">From Location</th>
                                                <th width="25%">To Location</th>
                                                <th width="25%">Transfer No</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">Movement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sum = 0; ?>
                                            @foreach ($stockmovementlocation as $key => $itml)
                                                <?php $sum += $itml->qty; ?>
                                                @if ($itml->from_location_id == auth()->user()->location->id )
                                                    <tr>
                                                        <td>ISSUED</td>
                                                        <td>{{ $itml->from_location_name }}</td>
                                                        <td>{{ $itml->to_location_name }}</td>
                                                        <td>{{ $itml->tr_no }}</td>
                                                        <td>{{ $itml->created_at }}</td>
                                                        <td>{{ $itml->qty }}</td>
                                                    </tr>
                                                @endif

                                                @if ($itml->to_location_id == auth()->user()->location->id )
                                                    <tr class="table-success">
                                                        <td>RECEIVED</td>
                                                        <td>{{ $itml->from_location_name }}</td>
                                                        <td>{{ $itml->to_location_name }}</td>
                                                        <td>{{ $itml->tr_no }}</td>
                                                        <td>{{ $itml->created_at }}</td>
                                                        <td>{{ $itml->qty }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr style="background-color:gray;font-size:20px;">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:right;font-size:20px;font-color:white"></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-4 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Location Wise Serial Movement </h3>
                                </div>

                                <div class="card-body" style="overflow-y: scroll;">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr style="background-color:gray;font-size:20px;font-color:white">
                                                <th width="10%">#</th>
                                                <th width="25%">Serial</th>
                                                <th width="25%">Location</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">User</th>
                                                <th width="20%">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sum = 0; ?>
                                            @foreach ($serial as $key => $se)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $se->serial_no }}</td>
                                                    <td>{{ $se->location }}</td>
                                                    <td>{{ $se->created_at }}</td>
                                                    <td>{{ $se->name }}</td>
                                                    <td>{{ $se->type }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Item Return Details</h3>
                                </div>

                                <div class="card-body" style="overflow-y: scroll;">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr style="background-color: gray; font-size: 20px; font-color: white">
                                                <th width="10%">#</th>
                                                <th width="25%">Item Number</th>
                                                <th width="25%">Return No</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">Quantity</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($itemReturnDetails as $key => $returnDetail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $returnDetail->item->item_no }} -
                                                        {{ $returnDetail->item->description }}</td>
                                                    <td>{{ $returnDetail->itemreturn->return_no }}</td>
                                                    <td>{{ $returnDetail->craeted_at }}</td>
                                                    <td>{{ $returnDetail->qty }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr style="background-color: gray; font-size: 20px;">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align:right; font-size:20px; font-color:white">QTY SUM</td>
                                                <td>{{ $itemReturnDetails->sum('qty') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- /.card-body -->
                </div>
            </section>

        </div>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#from_location').select2();
            $('#item').select2();
        });
    </script>
@endsection
