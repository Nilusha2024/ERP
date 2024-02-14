@extends('layouts.app')

@section('content')
    {{-- view a single transferRecord order in detail --}}

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
                            <li class="breadcrumb-item">Serials</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Transfer serials for item </h3>
                </div>
                <div class="card-body">

                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Serial</th>
                                <th>Transfer status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transferSerialRecordList as $transferSerialRecord)
                                <tr>
                                    <td>{{ $transferSerialRecord['item']['item_no'] }}</td>
                                    <td>{{ $transferSerialRecord['item']['description'] }}</td>
                                    <td>{{ $transferSerialRecord->serial_no }}</td>
                                    <td>
                                        @if ($transferSerialRecord->status == 1)
                                            <span class="badge badge-success">TRANSFERRED</span>
                                        @else
                                            <span class="badge badge-warning">PENDING</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection
