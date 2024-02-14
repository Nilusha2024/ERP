<!-- checkzone.blade.php -->

@extends('layouts.app')
@section('content')
    <script
        src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/af-2.2.0/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/cr-1.3.3/fc-3.2.2/fh-3.1.2/kt-2.2.1/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.2/datatables.js">
    </script>

    <style>
        /* Add your custom styles */
        .red-section {
            background-color: rgb(236, 98, 98);
            color: white;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .pending-box {
            background-color: rgba(0, 0, 0, 0);
            color: rgb(147, 0, 0);
            padding: 10px;
            font-size: 25px;
            font-weight: bold;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Zone Check</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Zone Check</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="red-section text-center">
                    මධ්යස්ථාන ඉන්වෙන්ටරි වැරදි නම්, <br>කළමනාකරුවන් එය සකස් කළ යුතුය.
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="pending-box">
                    Pending: {{ $pendingODSCount }}
                </div>
            </div>
        </div>
        <br>
        <form action="{{ route('checkzone.form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-4">

                <div class="form-group">
                    <label>Location</label>
                    <select class="form-control" name="location" id="location">
                        <option value="">
                            <-- Select --->
                        </option>
                        @foreach ($locationlist as $location)
                            <option value="{{ $location->id }}"
                                @if ($location_id == $location->id) selected="selected" @endif>
                                {{ $location->code }} - {{ $location->location }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-3">
                    <!-- catagory code input -->
                    <div class="form-group">
                        <button class="btn btn-success savebtn" type="submit">Search</button>
                    </div>
                </div>

            </div>
        </form>


        <body>

            <p></p>

            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">MR TR Pending</button>
                <button class="tablinks" onclick="openCity(event, 'Paris')">Stock</button>
                <button class="tablinks" onclick="openCity(event, 'Tokyo')">Item Return</button>
            </div>

            <div id="London" class="tabcontent">
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="card-title">mr_tr pending</h3>

                    </div>

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
                                        @if ($transfer->status == 1)
                                            <span class="badge badge-danger">Pending</span>
                                        @elseif($transfer->status == 2)
                                            <span class="badge badge-warning">Process</span>
                                        @elseif($transfer->status == 3)
                                            @if ($user['userrole']['id'] == 4 || $user['userrole']['id'] == 9 || $user['userrole']['id'] == 11)
                                                @if ($transfer->to_location_id == $user->location_id)
                                                    <span class="badge badge-success">Need to add stocl</span>
                                                @else
                                                    <span class="badge badge-info">Dispatched</span>
                                                @endif
                                            @elseif($user['userrole']['id'] == 3 || $user['userrole']['id'] == 12)
                                                <in<span class="badge badge-success">Need to add stocl</span>
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
                </div>
            </div>

            <div id="Paris" class="tabcontent">
                <div class="card-header">
                    <h3 class="card-title">Stock Report</h3>

                </div>
                <table id="" class="display table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Location Code</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Item Code</th>
                            <th>Item</th>
                            <th>Item Type</th>
                            <th>Quantity</th>
                            <th>Serial Numbers</th>
                            <!-- <th>Balance</th> -->
                            <!-- <th>Status</th>
                                                            <th hidden>Created</th>
                                                            <th hidden>Updated</th> -->
                            <!-- <th Colspan="2">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocklist as $sl)
                            <tr>

                                @if (isset($sl['location']))
                                    <td>{{ $sl['location']['code'] }}</td>
                                    <td><a>{{ $sl['location']['location'] }}</a></td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td><a>{{ $sl['item']['category'] }}</a></td>
                                <td>{{ $sl['item']['item_no'] }}</td>
                                <td>{{ $sl['item']['description'] }}</td>
                                <td>{{ $sl['item']['item_type'] }}</td>
                                <td>
                                    <a href="#" class="link-info" data-toggle="modal" data-target="#exampleModalLong"
                                        onclick="set_data(<?php echo $sl['item']['id']; ?>);">
                                        <?php
                                        $qty = $sl->qty;
                                        if ($qty != 0) {
                                            echo $qty;
                                        }
                                        ?>
                                    </a>
                                </td>

                                <!-- <td>{{ $sl->balance }}</td> -->
                                <!-- <td>
                                                                @if ($sl->status == 1)
    <span class="badge badge-success">ACTIVE</span>
@else
    <span class="badge badge-danger">BLOCK</span>
    @endif
                                                            </td>
                                                            <td hidden>{{ $sl->created_at }}</td>
                                                            <td hidden>{{ $sl->updated_at }}</td> -->
                                <!-- <td>
                                                                <div class="btn btn-default btn-sm btn-flat">
                                                                    Edit
                                                                </div>
                                                            </td> -->
                                <td>
                                    @if (count($sl->serials) > 0)
                                        <ul>
                                            @foreach ($sl->serials as $serial)
                                                @if ($serial->serial_no !== null)
                                                    <li>{{ $serial->serial_no }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
    </div>

    <div id="Tokyo" class="tabcontent">



    </div>

    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>


    <br>


    <div class="container mt-3">
        <br>


        <form action="{{ route('zone.check.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="location" id="location" value="{{ $location_id }}">

            <div class="mt-3">
                <label for="comments">Comments:</label>
                <textarea class="form-control" id="comments" name="comments"></textarea>
            </div>

            <div class="mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkZoneManager" name="checkZoneManager">
                    <label class="form-check-label" for="checkZoneManager">Check By:</label>
                </div>
                <select class="form-control" name="zoneuser" id="zoneuser">
                    <option value="">Select Manager</option>
                    @foreach ($zoneManagerList as $zoneManager)
                        <option value="{{ $zoneManager->id }}">{{ $zoneManager->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <label for="centerManagers">Enter Center Manager:</label>
                <select class="form-control" name="centeruser" id="centeruser">
                    <option value="">Select Center</option>
                    @foreach ($centerManagerList as $centerManager)
                        <option value="{{ $centerManager->id }}">{{ $centerManager->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <br>
        </form>
        <!-- Display current checked zones table -->
        <div class="card">
            <div class="card-header">
                <h3>Checked Zone Status</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Locations</th>
                            <th>Status</th>
                            <th>Checked Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checkedZones as $checkedZone)
                            <tr>
                                <td class="table-success">{{ $checkedZone->location_id }}</td>
                                <td class="table-success">checked</td>
                                <td class="table-success">{{ $checkedZone->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('table.display').DataTable();
        });
    </script>


    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#location').select2();
            $('#zoneuser').select2();
            $('#centeruser').select2();
        });
    </script>
@endsection
