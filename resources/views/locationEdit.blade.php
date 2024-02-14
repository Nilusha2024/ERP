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
                    <h3 class="card-title">Edit Location</h3>

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

                    <form action="{{ route('locationupdate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <!-- text input -->
                            <input type="hidden" name="id" value="{{$locátion[0]['id']}}"
                                <div class="form-group">
                                    <!-- dop down list  -->
                                    <label>Warehouse Type </label>
                                    <select class="form-control" name="warehouse_type_id" id="warehouse_type_id">
                                        @foreach($type as $typ)
                                        <option value="{{$typ->id}}" @if($typ->id == $locátion[0]['warehouse_type_id']) selected @endif>{{$typ->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <!-- catagory code input -->
                                <div class="form-group">
                                    <label>Category Code </label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="code" id="code" value="{{$locátion[0]['code']}}" readonly>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <!-- location input -->
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="location" id="location" value="{{$locátion[0]['location']}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="reset" name="reset" value="Reset" class="btn btn-dark">
                            <button type="submit" class="btn btn-primary" >Update</button>
                        </div>
                </div>
                </form>
                
            </div>
        </section>

    </div>
</div>
@endsection