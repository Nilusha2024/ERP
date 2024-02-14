@extends('layouts.app')

@section('content')

    {{-- section header --}}
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <!-- <h1>Item</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- user registration form --}}
    <div class="container-fluid mt-3">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create user</h3>
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
                <form method="POST" action="{{ route('create_user') }}" enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="register_name" id="register_name"
                                placeholder="Enter full name" value="{{ old('register_name') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>

                        {{-- role selector --}}
                        <div class="input-group mb-3">
                            <select class="form-control" style="appearance:none;" name="register_role" id="register_role">
                                <option value="" disabled selected hidden>Role</option>
                                @foreach ($userroles as $userrole)
                                    <option value="{{ $userrole->id }}"
                                        {{ old('register_role') == $userrole->id ? 'selected' : '' }}> {{ $userrole->role }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user-tie"></span></div>
                            </div>
                        </div>


                        {{-- location selector --}}
                        <div class="input-group mb-3">
                            <select class="form-control" style="appearance:none;" name="register_location"
                                id="register_location">
                                <option value="" disabled selected hidden>Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('register_location') == $location->id ? 'selected' : '' }}>
                                        {{ $location->location }} </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-map"></span></div>
                            </div>
                        </div>


                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="register_email" id="register_email"
                                placeholder="Enter email" value="{{ old('register_email') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="register_username" id="register_username"
                                placeholder="Enter username" value="{{ old('register_username') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-address-card"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="register_password" id="register_password"
                                placeholder="Password" value="{{ old('register_password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="register_password_confirmation"
                                id="register_password_confirmation" placeholder="Confirm password"
                                value="{{ old('register_password_confirmation') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>

                        <div class="mb-3 mt-4 ms-2">
                            <div class="form-check">
                                <input id="show_password_check" class="form-check-input" type="checkbox">
                                <label class="form-check-label" for="show_password_check"> Show passwords </label>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

            </div>
        </div>
        <!-- /.card -->

    </div>
    {{-- user list --}}
    <div class="card m-2">
        <div class="card-header">
            <h3 class="card-title">User List</h3>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Location</th>
                        <th>Email</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($userlist as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user['userrole']['role'] }}</td>
                            <td>{{ $user['location']['location'] }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="row">
                                    {{-- to update --}}
                                    <a href="{{ route('edit_user', ['user' => $user]) }}"
                                        class="btn btn-default btn-sm btn-flat">
                                        Edit
                                    </a>
                                    {{-- delete --}}
                                    <form method="POST" action="{{ route('delete_user') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" class="form-control" name="user_id" id="user_id"
                                            value="{{ $user->id }}">
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this user ?')"
                                            class="btn btn-danger btn-sm btn-flat">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#register_role').select2();
            $('#register_location').select2();

            $('#show_password_check').on('change', function() {
                $('#register_password').prop('type', $('#show_password_check').prop('checked') == true ?
                    "text" :
                    "password");
                $('#register_password_confirmation').prop('type', $('#show_password_check').prop(
                    'checked') == true ?
                    "text" : "password");
            });
        });
    </script>

@endsection
