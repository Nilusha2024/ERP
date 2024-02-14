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
                                <li class="breadcrumb-item active">GRN Detail Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">GRN Detail Report</h3>

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
                                        <h3 class="card-title">GRN Detail Report</h3>
                                        <div style="float: right;">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>grn_id</th>
                                                    <th>item No</th>
                                                    <th>Discription</th>
                                                    <th>qty</th>
                                                    <th>status</th>
                                                    <th>created_at</th>
                                                    
                                                    {{-- <th>Action</th> --}}

                                                    <!-- <th Colspan="2">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($grnd as $GRND)
                                                    <tr>
                                                        <td>{{ $GRND->grn_id }}</td>
                                                        <td>{{ $GRND['item']['item_no'] }}</td>
                                                        <td>{{ $GRND['item']['description']}}</td>
                                                        <td>{{ $GRND->qty }}</td>
                                                        <td>{{ $GRND->status }}</td>
                                                        <td>{{ $GRND->created_at }}</td>
                                                        
                                                        {{-- <td><a href=""
                                                            class="btn btn-primary btn-sm">View</td> --}}
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

    <script>
        function change_status(id, status) {
            alert(status);
            // ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ url('/mr_status_update') }}",
                method: "POST",
                data: {
                    'id': id,
                    'status': status
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
