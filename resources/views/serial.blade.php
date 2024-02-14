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
                            <li class="breadcrumb-item active">Serial No. Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <!-- general form elements disabled -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Serial No. Report</h3>

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
                                    <h3 class="card-title">Serial No. Report</h3> 
                                    <div style="float: right;">
                                    <!-- <button type="button" class="btn btn-outline-danger btn-sm" >Export as PDF</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" >Export as Excel</button> -->
                                </div>
                                </div>
                                <div class="card-body">
                                    <!-- <form class="form-inline" method="GET">
                                        <div class="form-group mb-2">
                                            <input type="text" class="form-control" id="filter" name="q" placeholder="Search" value="">
                                        </div>
                                        <button type="submit" class="btn btn-default mb-2">Filter</button>
                                    </form> -->
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th >Serial No.</th>
                                                    <th >Location</th>
                                                    <th >Item</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($seriallist as $key => $sl)
                                                <tr>
                                                    <td>{{$key + 1}}</td>
                                                    <td >{{$sl->serial_no}}</td>
                                                    <td >{{$sl->location['location']}}</td>
                                                    <td >{{$sl->stock['item']['item_no']}} {{$sl->stock['item']['description']}}</td>
                                                    <td>
                                                        @if($sl->status == 1)
                                                        <span class="badge badge-success">ACTIVE</span>
                                                        @else
                                                        <span class="badge badge-danger">BLOCK</span>
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

@endsection
