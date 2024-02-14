@extends('layouts.app')

@section('content')
    {{-- view all po --}}

    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('po') }}">PO</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Purchase Orders</h3>
                </div>
                <div class="card-body">

                    <table  class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>PO ID</th>
                                <th>PO No</th>
                                <th>Vendor</th>
                                <th>Created by</th>
                                <th>Approved by finance</th>
                                <th>Approved by Ed</th>
                                
                                <th>Download</th>
                                <th>Print</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($polist as $po)
                                <tr>
                                    <td>{{ $po->id }}</td>
                                    <td>{{ $po->po_no }}</td>
                                    <td>{{ $po['vendor']['name'] }}</td>
                                    <td>{{ $po['createdBy']['name'] }}</td>
                                    <td>
                                        @if ($po->approved_by_finance == 1)
                                            <span class="badge badge-success">APPROVED</span>
                                        @elseif($po->approved_by_finance == 2)
                                            <span class="badge badge-danger">REJECTED</span>
                                        @else
                                            <span class="badge badge-warning">PENDING</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($po->approved_by_ed == 1)
                                            <span class="badge badge-success">APPROVED</span>
                                        @elseif($po->approved_by_ed == 2)
                                            <span class="badge badge-danger">REJECTED</span>
                                        @else
                                            <span class="badge badge-warning">PENDING</span>
                                        @endif
                                    </td>
                                    
                                    <td><a  href="{{ route('generatePoPDF', ['ID' => $po->id]) }}"
                                                            class="btn btn-link btn-sm">Download</a></td>
                                   <td><a target="_blank" href="{{ route('printpo', ['ID' => $po->id]) }}"
                                                            class="btn btn-link btn-sm">Print</a></td>                        
                                    <td>
                                        @if ($po->status == 1)
                                            <span class="badge badge-success">COMPLETE</span>
                                        @else
                                            <span class="badge badge-warning">PENDING</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('view_po_details', ['po' => $po]) }}"
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
                    {!! $polist->links() !!}
                </div>
            </div>

        </section>

    </div>
@endsection
