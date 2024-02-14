@extends('layouts.app')

@section('content')

<div class="container-fluid">
  <div style="min-height: 1345.31px;">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Item</h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('itemstore') }}">Items</a></li>
              <li class="breadcrumb-item active">Item Edit</li>
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
          <form action="{{ route('itemedit', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-sm-3">
                <!-- text input -->
                <div class="form-group">
                  <label>Item No *</label>
                  <input type="text" class="form-control" placeholder="Enter ..." name="item_no" id="item_no"
                    value="{{$item->item_no}}">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>Category Code *</label>
                  <select class="form-control" name="category_code_id" id="category_code_id"
                    value="{{$item->category_code_id}}">

                    
                    <option value=""><-- Select ---></option>
                    @foreach($categorycode as $ca)
                    <option value="{{$ca->id}}" {{ old('category_code_id') == $ca->id ? 'selected' : ''  }}>{{$ca->code}}</option>
                    @endforeach

                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>UOM *</label>
                  <select class="form-control" name="uom_id" id="uom_id" value="{{$item->uom_id}}">
                    <option value=""><-- Select ---></option>
                    @foreach($uom as $u)
                    <option value="{{$u->id}}">{{$u->code}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label>MR Status *</label>
                  <select class="form-control" name="mr_status"  id="mr_status" value="{{ $item->mr_status }}" >
                    <option value="" ><-- Select --></option>
                                          
                        <option value=1>Active</option>
                        <option value=0>Inactive</option>
                   

                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <!-- textarea -->
                <div class="form-group">
                  <label>Description *</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." name="description" id="description">{{$item->description}}</textarea> 
                </div>
              </div>
            </div>
            <div class="relative flex items-center min-h-screen justify-center overflow-hidden">
              <!-- <form action="{{ route('image.store') }}" method="POST" class="shadow p-12" enctype="multipart/form-data">  -->
              @csrf
              <label class="block mb-4">
                <label>Choose Image *</label>
                <span class="sr-only">Choose Image</span>
                <input type="file" name="image" id="image" value="{{$item['image']}}"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <img src="{{ asset($item->image) }}" width='60' height='60' class="img img-responsive" />
                @error('image')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
              </label>

            </div>
            <div class="form-group">
              <input type="reset" name="reset" value="Reset" class="btn btn-dark">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
        </form>
      </div>
    </section>
  </div>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script>
    document.getElementsByName('category_code_id')[0].value = "{{ $item->category_code_id }}";
    document.getElementsByName('uom_id')[0].value = "{{ $item->uom_id }}";
    document.getElementsByName('mr_status')[0].value = "{{ $item->mr_status }}";
    document.getElementsByName('description').text = "{{ $item->description }}";

    if(mr_status == 1){
      value = 'Active'
    }else{
      value = 'Inactive'}
  </script>
@endsection