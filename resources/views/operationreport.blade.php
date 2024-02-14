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
                    <h3 class="card-title">MR Request Accept Pending Location List</h3>

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
                                
                                <div class="col-sm-3">
                                    <!-- catagory code input -->
                                    <div class="form-group">
                                    <button class="btn btn-success savebtn" type="submit">Search</button>
                                    </div>
                                </div>
                                
                            </div>

                    </div>
                    </form>
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th >Location</th>
                                                    <th>Order Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($order as $o)
                                               <tr>
                                                    <th >{{$o->location}}</th>
                                                    <th>{{$o->lcount}}</th>
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


{{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
<script>
</script>
@endsection
