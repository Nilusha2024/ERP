@extends('layouts.app')

@section('content')
    {{-- not a good practice, so place somewhere else sometime later --}}
    <style></style>

    <div class="container-fluid">
        <div style="min-height: 1345.31px;">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <!-- <h1>Item</h1> -->
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>Stock Ledger</div>
                            <div class="text-secondary">
                                <a class="ledger-option mx-2 active" href="{{ route('stock_ledger') }}">Base Ledger</a> |
                                <a class="ledger-option mx-2" href="{{ route('stock_transfer_ledger') }}">Transfer
                                    Ledger</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- filters row ig --}}
                        <div class="d-flex justify-content-between mb-3">

                            {{-- left --}}
                            <div class="d-flex align-items-center">
                                <div class="text-bold mx-1">Location:</div>
                                <input id="location_to_focus" class="form-control w-auto mx-2" type="text">
                                <button id="focus_location" class="btn btn-warning" disabled>Find</button>
                            </div>

                        </div>

                        {{-- Stock Ledger --}}

                        <table id="dataTable" class="table table-striped table-bordered table-responsive">
                            <thead>
                                <tr>
                                    {{-- table header --}}
                                    @foreach (array_keys(get_object_vars($stock_ledger[0])) as $header)
                                        <th id="{{ $header }}" class="stock-transfer-ledger-header">
                                            {{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_ledger as $record)
                                    <tr>
                                        @foreach (get_object_vars($record) as $property => $location_qty)
                                            <td>{{ $location_qty }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

            </section>
        </div>

    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            // enable the buttons after load
            $("#focus_location").prop("disabled", false);

        });

        // focus location header
        $('#focus_location').on('click', function() {
            let location = $('#location_to_focus').val();
            scrollToHeader(location);
        });


        function scrollToHeader(location) {
            let header = $('#' + location);
            if (header.length > 0) {
                $('.table-responsive').animate({
                    scrollLeft: header.offset().left - $('.table-responsive').offset().left
                }, 500);
            } else {
                console.log('Header not found');
            }
        }
    </script>
@endsection
