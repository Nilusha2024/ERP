<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Price card details</title>
</head>

<body>
    {{-- Price card details table goes here --}}
    <div class="p-3">
        <table id="price_card_details" class="table table-striped table-bordered display">
            <thead>
                <tr>
                    <th class="table-warning text-center">Prices for {{ $item }}</th>
                    <th class="table-warning text-center">Item type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($priceCards as $priceCard)
                    <tr>
                        <td>{{ $priceCard->price }}
                            <input class="current_price" type="hidden" value="{{ $priceCard->price }}" />
                        </td>
                        <td>
                            @if ($priceCard->item_type == 1)
                                <span>New</span>
                            @elseif ($priceCard->item_type == 2)
                                <span>Used</span>
                            @elseif($priceCard->item_type == 3)
                                <span>Discarded</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#price_card_details').DataTable({
                scrollY: '200px',
                scrollCollapse: true,
                paging: false,
            });
        });
    </script>

</body>

</html>
