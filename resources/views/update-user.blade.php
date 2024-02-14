@extends('layouts.app')

@section('content')

    {{-- user update form --}}
    <div class="container-fluid mt-3">

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Update user</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                <!-- form start -->
                <form method="POST" action="{{ route('update_user') }}" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- hidden field to store id --}}
                        <input type="hidden" class="form-control" name="user_id" id="user_id"
                            value="{{ $user->id }}">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="update_name" id="update_name"
                                placeholder="Enter new name" value="{{ $user->name }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>

                        {{-- role selector --}}
                        <div class="input-group mb-3">
                            <select class="form-control" style="appearance:none;" name="update_role" id="update_role">
                                <option value="" disabled selected hidden>New role</option>
                                @foreach ($userroles as $userrole)
                                    <option value="{{ $userrole->id }}"
                                        {{ old('update_role') == $userrole->id ? 'selected' : '' }}> {{ $userrole->role }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                            </div>
                        </div>


                        {{-- location selector --}}
                        <div class="input-group mb-3">
                            <select class="form-control" style="appearance:none;" name="update_location"
                                id="update_location">
                                <option value="" disabled selected hidden>New location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('update_location') == $location->id ? 'selected' : '' }}>
                                        {{ $location->code }} </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-map"></span></div>
                            </div>
                        </div>


                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="update_email" id="update_email"
                                placeholder="Enter new email" value="{{ $user->email }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                        </div>


                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="update_username" id="update_username"
                                placeholder="Enter new username" value="{{ $user->username }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-address-card"></span></div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
        <!-- /.card -->

    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#update_role').select2();
            $('#update_location').select2();
        });

        document.getElementsByName('update_role')[0].value = "{{ $user->role_id }}";
        document.getElementsByName('update_location')[0].value = "{{ $user->location_id }}";
    </script>

    {{-- page load tested and is working fine --}}
@endsection
