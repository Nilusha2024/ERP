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
                                <li class="breadcrumb-item active">Location</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Location</h3>

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

                        <form action="{{ route('location') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>Warehouse Type </label>
                                        <select class="form-control" name="warehouse_type_id" id="warehouse_type_id">
                                            <option value="">
                                                <-- Select --->
                                            </option>
                                            @foreach ($type as $typ)
                                                <option value="{{ $typ->id }}">{{ $typ->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                        <label>Category Code </label>
                                        <input type="text" class="form-control" placeholder="Enter ..." name="code"
                                            id="code">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <!-- location input -->
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" class="form-control" placeholder="Enter ..." name="location"
                                            id="location">
                                    </div>
                                </div>
                            </div>
                            {{-- submit button --}}
                            <div class="form-group">
                                <input type="reset" name="reset" value="Reset" class="btn btn-dark">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-12 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Item List</h3>
                                </div>

                                <div class="card-body">
                                    <table id="dataTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Warehouse Type</th>
                                                <th>Code</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                                <!--Colspan=" 2" -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($locátion as $lo)
                                                <tr>
                                                    <td>{{ $lo->id }}</td>
                                                    <td>{{ $lo['type']['type'] }}</td>
                                                    <td>{{ $lo->code }}</td>
                                                    <td>{{ $lo->location }}</td>

                                                    <td>
                                                        <div class="btn btn-default btn-sm btn-flat">
                                                            <a
                                                                href="{{ url('locationEdit?id=') }}{{ $lo->id }}">Edit</a>
                                                        </div>
                                                    </td>
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
@endsection
