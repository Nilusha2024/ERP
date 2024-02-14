@extends('layouts.app')

@section('content')
    {{-- view a single poDetailRecord order in detail --}}

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
                            <li class="breadcrumb-item"><a href="{{ route('po') }}">PO</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('view_all_po') }}">View</a></li>
                            <li class="breadcrumb-item">{{ $po->id }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            {{-- user role id --}}
            <input type="hidden" id="user_role" value="{{ $user->role_id }}">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">PO ID {{ $po->id }} : Purchase Order Details </h3>
                </div>
                <div class="card-body">

                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($poDetailsRecordList as $poDetailRecord)
                                <tr>
                                    <td>{{ $poDetailRecord['item']['item_no'] }}</td>
                                    <td>{{ $poDetailRecord['item']['description'] }}</td>
                                    <td>{{ $poDetailRecord->qty }}</td>
                                    <td>{{ $poDetailRecord->price }}</td>
                                    <td>
                                        @if ($poDetailRecord->status == 1)
                                            <span class="badge badge-success">COMPLETE</span>
                                        @else
                                            <span class="badge badge-warning">PENDING</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($user->role_id == 10)
                    @if ($po->approved_by_ed == 0)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-success" value="1"
                                        onclick="giveApproval(this.value)">Approve</button>
                                </div>
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-danger" value="2"
                                        onclick="giveApproval(this.value)">Reject</button>
                                </div>
                            </div>
                        </div>
                    @elseif($po->approved_by_ed == 1)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <button type="submit" class="btn btn-secondary" disabled>Approved</button>
                                </div>
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-danger" value="2"
                                        onclick="giveApproval(this.value)">Reject</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-success" value="1"
                                        onclick="giveApproval(this.value)">Approve</button>
                                </div>
                                <div class="m-1">
                                    <button type="submit" class="btn btn-secondary" disabled>Rejected</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($user->role_id == 5)
                    @if ($po->approved_by_finance == 0)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-success" value="1"
                                        onclick="giveApproval(this.value)">Approve</button>
                                </div>
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-danger" value="2"
                                        onclick="giveApproval(this.value)">Reject</button>
                                </div>
                            </div>
                        </div>
                    @elseif ($po->approved_by_finance == 1)
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <button type="submit" class="btn btn-secondary" disabled>Approved</button>
                                </div>
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-danger" value="2"
                                        onclick="giveApproval(this.value)">Reject</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <hr class="mr-3 ml-3">
                            <div class="row d-flex justify-content-end m-3">
                                <div class="m-1">
                                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                                    <input type="hidden" id="po" value="{{ $po->id }}">
                                    <button type="submit" class="btn btn-success" value="1"
                                        onclick="giveApproval(this.value)">Approve</button>
                                </div>
                                <div class="m-1">
                                    <button type="submit" class="btn btn-secondary" disabled>Rejected</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

            </div>

        </section>
    </div>

    <script>
        function giveApproval(status) {

            var po = $('#po').val();
            var user_role = $('#user_role').val();

            var approval = (user_role == 5) ? 'finance' : (user_role == 10) ? 'ED' : 'NONE';
            var action = (status == 1) ? 'approval' : 'rejection';

            Swal.fire({
                icon: 'question',
                title: 'Confirm ' + approval + ' ' + action + ' ?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ url('po/approve') }}",
                        method: "POST",
                        data: {
                            'po': po,
                            'approval': approval,
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
            })

        }
    </script>
@endsection
