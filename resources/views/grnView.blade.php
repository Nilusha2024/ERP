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
                                <li class="breadcrumb-item active">GRN Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">GRN Report</h3>

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
                                        <!-- <h3 class="card-title">GRN Report</h3> -->
                                        <div style="float: right;">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-bordered" >
                                            <thead>
                                                <tr>
                                                    <th>GRN No</th>
                                                    <th>PO NO</th>
                                                    <th>Supplier Invoice</th>
                                                    <th>Created At</th>
                                                    <th>Print</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                    {{-- <th>Action</th> --}}

                                                    <!-- <th Colspan="2">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($grn as $GRN)
                                                    <tr>
                                                        <td>{{ $GRN->grn_no }}</td>
                                                        <td>{{ $GRN->po_no }}</td>
                                                        <td>{{ $GRN->ref_no }}</td>
                                                        <td>{{ $GRN->created_at }}</td>
                                                        <td><a href="{{ route('generateGRNPDF', ['ID' => $GRN->id]) }}"
                                                            class="btn btn-link btn-sm btn-sm">Download</a></td>
                                                        <td><a target="_blank" href="{{ route('printGrn', ['ID' => $GRN->id]) }}"
                                                            class="btn btn-link btn-sm">Print</a></td>       
                                                        <!-- <td>
                                                            @if ($GRN->created_by == 1)
                                                                <span class="badge badge-danger">Pending</span>
                                                            @else
                                                                <span class="badge badge-danger">Recived</span>
                                                            @endif
                                                        </td> -->
                                                       
                                                        <td>
                                                            @if ($GRN->status == 1)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">Deactive</span>
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('grndetail', ['GRN' => $GRN]) }}"
                                                            class="btn btn-primary btn-sm">View</a></td>
                                                        {{-- <td><a href=""
                                                            class="btn btn-primary btn-sm">View</td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <br>
                                        {!! $grn->links() !!}
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
