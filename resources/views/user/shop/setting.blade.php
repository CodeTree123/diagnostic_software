@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Add your Organization details</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('user.shop.data')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="form-label">Name of your Organization</div>
                            <input type="text" name="name" class="form-control" value="{{$shop->name ?? ''}}">
                        </div>
                        <div class="form-group">
                            <div class="form-label">Address</div>
                            <input type="text" name="address" class="form-control" value="{{$shop->address ?? ''}}">
                        </div>
                        <div class="form-group">
                            <div class="form-label">Logo</div>
                            <input type="file" name="logo" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection