@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <h1>Item</h1> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Serial Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <form action="{{ route('serailedit') }}" method="post">
            @csrf
            <div class="form-group">
                <select class="form-control" name="serial_no" id="serial_no" onchange="onSourceSerialSelect()">
                    <option value="" disabled selected hidden>Serial</option>
                    @foreach ($serial as $s)
                        <option value="{{ $s->id }}"  data-location-id="{{ $s->location->id }}" data-location-name="{{ $s->location->location }}">{{ $s->serial_no }}</option>
                    @endforeach
                </select>
            </div>
            <div id="locationDisplay"></div>
            <br>
            <div class="form-group">
                <label for="new_serial">New Serial:</label>
                <input type="text" class="form-control" id="new_serial" name="new_serial" placeholder="New Serial Number"
                    required>
            </div>
            <div class="form-group">
                <label for="edit_location">Edit Location:</label>
                <select class="form-control" name="edit_location" id="edit_location" required>
                    @foreach ($location as $locationId => $locationName)
                        <option value="{{ $locationId }}">{{ $locationName }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Serial</button>
        </form>
    </div>

    <script>
    function onSourceSerialSelect() {
        var serialSelect = document.getElementById("serial_no");
        var selectedSerial = serialSelect.options[serialSelect.selectedIndex];
        var locationDisplay = document.getElementById("locationDisplay");
        var editLocationSelect = document.getElementById("edit_location");

        if (selectedSerial) {
            var locationId = selectedSerial.getAttribute("data-location-id");
            var locationName = selectedSerial.getAttribute("data-location-name");

            // Display the location ID in front of the current location
            locationDisplay.innerText = "Current Location (ID: " + locationId + "): " + locationName;

            // Set the selected location in the edit_location select box
            editLocationSelect.value = locationId;
        } else {
            locationDisplay.innerText = "";
            editLocationSelect.value = "";
        }
    }

    @if (session('success'))
        Swal.fire('Success', '{{ session('success') }}', 'success');
    @elseif (session('error'))
        Swal.fire('Error', '{{ session('error') }}', 'error');
    @endif
</script>



    <!-- Add these lines in the <head> section of your HTML or layout file -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#serial_no').select2();
        });

        @if (session('success'))
            Swal.fire('Success', '{{ session('success') }}', 'success');
        @elseif (session('error'))
            Swal.fire('Error', '{{ session('error') }}', 'error');
        @endif
    </script>
    <script>
        @if (session('success'))
            Swal.fire('Success', '{{ session('success') }}', 'success');
        @endif
    
        @error('new_serial')
            Swal.fire('Error', '{{ $message }}', 'error');
        @enderror
    </script>
    
@endsection
