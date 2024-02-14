<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order details</title>
</head>

<body>
    {{-- Order details table goes here --}}
    <div class="p-3">
        <table id="order_details" class="table table-striped table-bordered display">
            <thead>
                <tr>
                    <th class="table-warning">Item No</th>
                    <th class="table-warning">Center request qty</th>
                    <th class="table-warning">Store approved qty</th>
                    <th class="table-warning">Center recieved qty</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail['item']['item_no'] }}</td>
                        <td>{{ $detail->center_request_qty }}</td>
                        <td>{{ $detail->store_approved_qty }}</td>
                        <td>{{ $detail->center_received_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#order_details').DataTable({
                scrollY: '200px',
                scrollCollapse: true,
                paging: false,
            });
        });
    </script>

</body>

</html>
