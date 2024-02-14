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
                                <li class="breadcrumb-item active">Stock Ledger</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <p class="mr-2">Stock Transfer Ledger </p>
                                @if (!($from_date == '1970-01-01'))
                                    <p class="ml-1 text-bold">From : {{ $from_date }} -</p>
                                @endif
                                <p class="ml-1 text-bold">Upto : {{ $to_date }}</p>
                            </div>
                            <div class="text-secondary">
                                <a class="ledger-option mx-2 active" href="{{ route('stock_ledger') }}">Base
                                    Ledger</a> |
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


                            {{-- right --}}
                            <form action="{{ route('stock_transfer_ledger_filtered') }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center">
                                    <div class="text-bold mx-1">From:</div>
                                    <input id="from_date" name="from_date" class="form-control w-auto mx-2" type="date">
                                    <div class="text-bold mx-1">To:</div>
                                    <input id="to_date" name="to_date" class="form-control w-auto mx-2" type="date">
                                    <button id="filter_by_date" class="btn btn-primary" type="submit"
                                        disabled>Filter</button>
                                </div>
                            </form>

                        </div>

                        {{-- Stock Transfer Ledger --}}

                        <table id="dataTable" class="table table-striped table-bordered table-responsive">
                            <thead>
                                <tr>
                                    {{-- table header --}}
                                    @foreach (array_keys(get_object_vars($stock_transfer_ledger[0])) as $header)
                                        <th id="{{ $header }}" class="stock-transfer-ledger-header">
                                            {{ $header }}</th>
                                    @endforeach
                                    <th>Total Row Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_transfer_ledger as $record)
                                    <tr>
                                        @php
                                            $totalRowQty = 0;
                                        @endphp
                                        @foreach (get_object_vars($record) as $property => $location_qty)
                                            <td>{{ $location_qty }}</td>
                                            @if (is_numeric($location_qty))
                                                @php
                                                    $totalRowQty += $location_qty;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <td><b>{{ $totalRowQty }}</b></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @php
                                        $totalColumnQty = array_fill_keys(array_keys(get_object_vars($stock_transfer_ledger[0])), 0);
                                    @endphp
                                    @foreach ($stock_transfer_ledger as $record)
                                        @foreach (get_object_vars($record) as $property => $location_qty)
                                            @if (is_numeric($location_qty))
                                                @php
                                                    $totalColumnQty[$property] += $location_qty;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @foreach ($totalColumnQty as $totalQty)
                                        <td><b>{{ $totalQty }}</b></td>
                                    @endforeach
                                    <td></td> {{-- Placeholder for the total of the total --}}
                                </tr>
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
            $("#filter_by_date").prop("disabled", false);
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
