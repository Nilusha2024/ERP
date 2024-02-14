@extends('layouts.app')

@section('content')




    <div class="container-fluid">
        <div style="min-height: 1345.31px;">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Item Return</li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Item</h3>
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
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        <form action="{{ route('itemstore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Item No *</label>
                                        <input type="text" class="form-control" placeholder="Enter ..." name="item_no"
                                            id="item_no">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Category Code *</label>
                                        <select class="form-control" name="category_code_id" id="category_code_id">
                                            <option value=""><-- Select --></option>
                                            @foreach ($categorycode as $ca)
                                                <option value="{{ $ca->id }}">{{ $ca->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>UOM *</label>
                                        <select class="form-control" name="uom_id" id="uom_id">
                                            <option value=""><-- Select --></option>
                                            @foreach ($uom as $u)
                                                <option value="{{ $u->id }}">{{ $u->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>MR Status *</label>
                                        <select class="form-control" name="mr_status" id="mr_status">
                                            <option value="" disabled selected hidden><-- Select --></option>
                                            <option value="1">MR</option>
                                            <option value="0">SERIAL</option>
                                            <option value="2">NON SERIAL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label>Description *</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter ..." name="description" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                  <label>Item Type *</label>
                                  <select class="form-control" name="item_type" id="item_type">
                                      <option value="" disabled selected hidden><-- Select --></option>
                                      <option value="CONSUMABLE">Consumable</option>
                                      <option value="CAPITAL">Capital</option>
                                  </select>
                              </div>
                          </div>
                            <div class="relative flex items-center min-h-screen justify-center overflow-hidden">
                                <!-- <form action="{{ route('image.store') }}" method="POST" class="shadow p-12" enctype="multipart/form-data"> -->
                                @csrf
                                <label class="block mb-4">
                                    <label>Choose Image *</label>
                                    <!-- <input class="form-control" name="image" type="file" id="image"> -->

                                    <span class="sr-only">Choose Image</span>
                                    <input type="file" name="image" id="image"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                    @error('image')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </label>
                                <!-- <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded">Submit</button>               -->
                                <!-- </form> -->
                            </div>
                            <div class="form-group">
                                <input type="reset" name="reset" value="Reset" class="btn btn-dark">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-12 form-grop">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Item List</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped table-bordered" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Item No</th>
                                                <th>Description</th>

                                                <th>Category Code</th>
                                                <th>UOM</th>
                                                <th>Price</th>
                                                <th>Price Date</th>
                                                <th>Type</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>MR Status</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($itemlist as $il)
                                                <tr>
                                                    <td><a>{{ $il->item_no }}</a></td>
                                                    <td>{{ $il->description }}</td>
                                                    @if (isset($il->uom))
                                                        <td>{{ $il->uom }} </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    @if (isset($il->categorycode))
                                                        <td>{{ $il->categorycode['description'] }} </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    @if (sizeof($il->price) > 0)
                                                        <?php
                                                        $manage = json_decode($il->price, true);
                                                        ?>
                                                        <td><?php print_r($manage[0]['price']); ?> </td>
                                                        <td><?php print_r(date($manage[0]['created_at'])); ?> </td>
                                                    @else
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                    @if (isset($il->item_type))
                                                        <td>{{ $il->item_type }} </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td><img src="{{ asset($il->image) }}" width= '60' height='60'
                                                            class="img img-responsive" /></td>
                                                    <!-- <td><img src="{{ asset($il->image) }}" width= '60' height='60' class="img img-responsive" /></td> -->

                                                    <td>
                                                        @if ($il->status == 1)
                                                            <span class="badge badge-success">ACTIVE</span>
                                                        @else
                                                            <span class="badge badge-danger">BLOCK</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($il->mr_status == 1)
                                                            <span class="badge badge-success">ACTIVE</span>
                                                        @else
                                                            <span class="badge badge-danger">INACTIVE</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="col-lg-2">
                                                            <a href="{{ route('itemedit', $il->id) }}"
                                                                class="btn btn-success" title="Add New Contact">Edit</a>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </section>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').dataTable({
                "pageLength": 20
            });
        });
    </script>
@endsection
