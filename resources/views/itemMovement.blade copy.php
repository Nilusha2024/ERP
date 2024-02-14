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
                                <li class="breadcrumb-item active">Item Movement Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Item Movement Report.</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('itemMovement') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>From</label>
                                        <input type="date" id="from" name="from" class="form-control"  @if(isset($from)) value="{{$from}}" @else value="{{date('Y-m-d')}}" @endif></input>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                        <label>To </label>
                                        <input type="date" id="to" name="to" class="form-control" @if(isset($to)) value="{{$to}}" @else value="{{date('Y-m-d')}}" @endif></input>
                                    </div>
                                </div>
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
                    <div class="row">
                        <div class="col-sm-12 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Item Movement Report</h3>
                                </div>

                                <div class="card-body" style="overflow-y: scroll; height:400px;">
                                    <table  class="table table-striped table-bordered">
                                        <thead>
                                            <!-- <tr>
                                                <td>Location</td>
                                            </tr>
                                            <tr>
                                                
                                                <th>Item No</th>
                                                <th>Item Description</th>
                                                <th>Movement</th>
                                            </tr> -->
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    @foreach ($stockmovementlocation as $lo)
                                                        <td>{{ $lo->code }}</td>
                                                    @endforeach
                                                </tr>
                                                    @foreach ($stockmovementitem as $key => $itml)
                                                        <tr>
                                                            <td>{{$key + 1}}</td>
                                                            <td>{{ $itml->item_no }}</td>
                                                            <td>{{ $itml->description }}</td>
                                                                <td>{{ $itml->qty }}</td>
                                                            <!-- <td>{{ $itml->qty }}</td> -->
                                                        </tr>
                                                    @endforeach
                                        </tbody>
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
            $('#location').select2();
            $('#item').select2();
        });
    </script>
@endsection
