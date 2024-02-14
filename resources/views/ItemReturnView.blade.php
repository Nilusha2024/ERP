@extends('layouts.app')

@section('content')
    {{-- view all item returns --}}

    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('itemeretur') }}">Return Items</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="card m-2">
                <div class="card-header">
                    <h3 class="card-title">Item Return</h3>
                </div>
                <div class="card-body">

                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Return Id </th>
                                <th>Return No </th>
                                <!-- <th>Return From</th>
                                        <th>Return To</th> -->
                                <th>Created By</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemReturnList as $itemReturn)
                                @foreach ($itemReturnDetailRecordList[$itemReturn->id] as $detail)
                                    <tr>
                                        <td>{{ $itemReturn->id }}</td>
                                        <td>{{ $itemReturn->return_no }}</td>
                                        <td>{{ $itemReturn['createdBy']['name'] }}</td>

                                        <td>{{ optional($detail->returnfrom)->location }}</td>
                                        <td>{{ optional($detail->returnto)->location }}</td>

                                        <td>{{ $itemReturn['created_at'] }}</td>
                                        <td>

                                            @if ($itemReturn->status == 1)
                                                <span class="badge badge-danger">Pending</span>
                                            @elseif($itemReturn->status == 2)
                                                <span class="badge badge-warning">Dispatched By Center</span>
                                            @elseif ($itemReturn->status == 3)
                                                <span class="badge badge-info">Confirm Return</span>
                                            @elseif($itemReturn->status == 4)
                                                <span class="badge badge-success">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row">
                                                <a href="{{ route('ItemReturnViewDetails', ['itemReturn' => $itemReturn]) }}"
                                                    class="btn btn-default btn-sm btn-flat">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </section>

    </div>
@endsection

<script>
    function pending(id, location) {
        var location_id = location.value;

        //console.log(location);
        $.ajax({
            url: "{{ route('changeState') }}",
            method: "GET",
            data: {
                "item": id,
                "location": location_id
            },
            success: function(data) {
                alert(data);
            },
        });

        //location.reload();
    }
</script>
