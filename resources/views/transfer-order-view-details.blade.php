@extends('layouts.app')

@section('content')
    {{-- view a single transferDetailRecord order in detail --}}

    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <h1>Item</h1> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('transfer_order') }}">Transfer Orders</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('view_all_transfer_orders') }}">View</a></li>
                            <li class="breadcrumb-item">{{ $transfer->id }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Transfer ID {{ $transfer->id }} : Transfer Order Details </h3>
                </div>
                <div class="card-body">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Transfer qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transferDetailsRecordList as $transferDetailRecord)
                                <tr>
                                    <td>{{ $transferDetailRecord['item']['item_no'] }}</td>
                                    <td>{{ $transferDetailRecord['item']['description'] }}</td>
                                    <td>{{ $transferDetailRecord->qty }}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('view_transfer_order_serials', ['transfer_detail' => $transferDetailRecord]) }}"
                                                class="btn btn-default btn-sm btn-flat">
                                                View Serials
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($user['userrole']['id'] == 3)
                    @if ($transfer->status == 1)
                        <div>
                            <hr class="mr-3 ml-3"><span class="badge badge-danger">Pending</span>
                            <div class="d-flex justify-content-end m-3">
                                <input type="hidden" id="transfer" value="{{ $transfer->id }}">
                            </div>
                            </hr>
                        </div>
                    @elseif($transfer->status == 2)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <span class="badge badge-warning">Process</span>
                                <input type="hidden" id="transfer" value="{{ $transfer->id }}">
                            </div>
                            </hr>
                        </div>
                    @elseif ($transfer->status == 3)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <span class="badge badge-info" style="margin: 15px">Dispatched</span>
                                <input type="hidden" id="transfer" value="{{ $transfer->id }}">
                                <button type="submit" class="btn btn-success" onclick="confirmReceival()">Confirm
                                    receival/Add Stock</button>
                            </div>
                            </hr>
                        </div>
                    @else
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <button type="submit" class="btn btn-secondary" disabled>Received</button>
                            </div>
                            </hr>
                        </div>
                    @endif
                @endif

                @if ($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11)
                    @if ($transfer->status == 1)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <span class="badge badge-danger">Pending</span>
                                <input type="button" class="btn btn-warning" value="Process"
                                    onclick="change_status(<?php echo $transfer->id; ?>,2)" />
                            </div>
                            </hr>
                        </div>
                    @elseif($transfer->status == 2)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <span class="badge badge-warning">Process</span>
                                <input type="button" class="btn btn-info" value="Dispatched"
                                    onclick="change_status(<?php echo $transfer->id; ?>,3)" />
                            </div>
                            </hr>
                        </div>
                   
                    @elseif($transfer->status == 4)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="d-flex justify-content-end m-3">
                                <span class="badge badge-success">Received</span>
                            </div>
                            </hr>
                        </div>
                    @endif
                @endif


            </div>

        </section>
    </div>

    <script>
        function change_status(id, status) {
            // ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ url('/tr_status_update') }}",
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

        function confirmReceival() {
            Swal.fire({
                icon: 'question',
                title: 'Confirm transfer receival ?',
                showCancelButton: true,
                confirmButtonText: 'Receive transfer',
            }).then((result) => {
                if (result.isConfirmed) {

                    var transfer = $('#transfer').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ url('transfer_order/receive') }}",
                        method: "POST",
                        data: {
                            'transfer': transfer,
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
            })
        }
    </script>
@endsection
