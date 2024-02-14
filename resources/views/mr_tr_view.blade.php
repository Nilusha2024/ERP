@extends('layouts.app')

@section('content')
    {{-- view all transfer orders --}}

    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('transfer_order') }}">Transfer Orders</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <!-- <div class="container">
                <form method="POST" action="{{ route('receive-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Receive All</button>
                </form>
            </div> -->
            
            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Transfer Orders</h3>
                </div>
                
                <div  class="card-body">

                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Transfer ID</th>
                                <th>Type</th>
                                <th>Created At</th>
                                <th>Created by</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Received by</th>
                                <th>Download</th>
                                <th>Print</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ods as $transfer)
                                <tr>
                                    <td>{{ $transfer->id }}</td>
                                    <td>
                                        @if ($transfer->type == 1)
                                            <span>DIRECT</span>
                                        @elseif($transfer->type == 2)
                                            <span>MR</span>
                                        @endif
                                    </td>
                                    <td>{{ $transfer['created_at'] }}</td>
                                    <td>{{ $transfer['createdBy']['name'] }}</td>
                                    <td>{{ $transfer['from']['location'] }}</td>
                                    <td>{{ $transfer['to']['location'] }}</td>
                                    <td>
                                        @if ($transfer->received_by == 0)
                                            <span>Not received yet</span>
                                        @else
                                            <span>{{ $transfer['receivedBy']['name'] }}</span>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('generateTOPDF', ['ID' => $transfer->id]) }}"
                                                            class="btn btn-link btn-sm">Download</a></td>
                                    <td><a target="_blank" href="{{ route('printto', ['ID' => $transfer->id]) }}"
                                                            class="btn btn-link btn-sm">Print</a></td>
                                    <td>                         
                                                            @if($transfer->status == 1)
                                                                <span class="badge badge-danger">Pending</span>
                                                                @if($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11 || $user['userrole']['id'] == 12)
                                                                    <input type="button" class="btn btn-warning" value="Process" onclick="change_status(<?php echo $transfer->id; ?>,2)"/>
                                                                @endif
                                                            @elseif($transfer->status == 2)  
                                                                <span class="badge badge-warning">Process</span> 
                                                                @if($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11 || $user['userrole']['id'] == 12)
                                                                    <input type="button" class="btn btn-info"  value="Dispatched" onclick="change_status(<?php echo $transfer->id; ?>,3)"/>
                                                                @endif
                                                            @elseif($transfer->status == 3)
                                                                
                                                                @if(($user['userrole']['id'] == 4 ||  $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11))
                                                                    @if($transfer->to_location_id == $user->location_id)
                                                                        <input type="button" class="btn btn-success"  value="Confirm receival/Add Stock" onclick="confirmReceival(<?php echo $transfer->id; ?>)"/>
                                                                    @else
                                                                    <span class="badge badge-info">Dispatched</span>
                                                                    @endif    
                                                                @elseif(($user['userrole']['id'] == 3 || $user['userrole']['id'] == 12))
                                                                    <input type="button" class="btn btn-success"  value="Confirm receival/Add Stock" onclick="confirmReceival(<?php echo $transfer->id; ?>)"/>
                                                                @endif
                                                            @elseif($transfer->status == 4)
                                                                <span class="badge badge-success">Received</span>
                                                            @endif
                                    </td>

                                    <td>
                                        <div class="row">
                                            <a href="{{ route('view_transfer_order_details', ['transfer' => $transfer]) }}"
                                                class="btn btn-default btn-sm btn-flat">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                   
                </div>
            </div>

        </section>

    </div>
    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
 $(document).ready(function() {
            //  $(".chosen-select").chosen({rtl: true});

            $('#created_by').select2();
            $('#from').select2();
            $('#to').select2();
            
        });

function change_status(id,status){
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
                            location.reload();
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

function confirmReceival(id) {
            Swal.fire({
                icon: 'question',
                title: 'Confirm transfer receival ?',
                showCancelButton: true,
                confirmButtonText: 'Receive transfer',
            }).then((result) => {
                if (result.isConfirmed) {

                    // var transfer = $('#transfer').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ url('transfer_order/receive') }}",
                        method: "POST",
                        data: {
                            'transfer': id,
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
