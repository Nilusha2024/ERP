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
                                <li class="breadcrumb-item active">Serial Movement Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Serial Movement Report.</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('serialnohistory') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <!-- text input -->

                                    <div class="form-group">
                                        <!-- dop down list  -->
                                        <label>Serial Number</label>
                                        <input type="text" id="serial_no" name="serial_no" class="form-control" Placeholder="Serial No"  @if(isset($serial_no)) value="{{$serial_no}}" @endif></input>
                                    </div>
                                </div>
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
                        
                        <div class="col-sm-12 form-grop">
                            <div class="card">
                                {{-- <div class="card-header">
                                    <h5><span class="badge rounded-pill bg-warning text-dark">{{$item[0]['item_no']}} {{$item[0]['description']}}</span> 
                                    <span class="badge rounded-pill bg-danger">{{$item[0]['serial_no']}}</span></h5>
                                </div> --}}

                                <div class="card-body" style="overflow-y: scroll;">
                                    <table  class="table table-striped table-bordered">
                                        <thead>
                                            <tr style="background-color:gray;font-size:20px;font-color:white"><th width="5%">#</th>
                                                <th width="20%">Location</th>
                                                <th width="20%">Date</th>
                                                <th width="20%">User</th>
                                                <th width="15%">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sum=0;?>
                                                    @foreach ($serial as $key => $se)
                                                    
                                                        <tr>
                                                            <td>{{$key + 1}}</td>
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
