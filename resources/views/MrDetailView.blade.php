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
                                <li class="breadcrumb-item active">MR Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">MR Details</h3>

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
                                        <h3 class="card-title">MR Details</h3>
                                        <div style="float: right;">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Item code</th>
                                                    <th>description</th>
                                                    <th>Store_dispatched_qty</th>

                                                    <!-- <th Colspan="2">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($oddetails as $od)
                                                <tr>
                                                    <td>{{ $od['order']['orderno'] }}</td>
                                                    <td>{{ $od['item']->item_no }}</td>
                                                    <td>{{ $od['item']->description }}</td>
                                                    <td>{{ $od->center_request_qty }}</td>

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
