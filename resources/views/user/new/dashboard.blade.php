@extends('user.layouts.new_app')
@section('content')
<div class="container">
        <div class="row justify-content-end" style="margin-right: 50px;margin-top:100px">
            <div class="col-md-6">
                <section class="mt-4 mb-4">
                    <h1 class="font-weight-bold text-success text-md-right" style="font-size: 54px">
                        Welcome to
                    </h1>
                    <h3 class=" text-success text-md-right">Diagnostic Management Software</h3>
                    <h3 class=" text-success text-md-right">by</h3>
                    <img style="width:300px;float:right; margin-top:-40px;" src="{{asset('newAssets/images/codetreelogo.png')}}" alt="">
                </section>
            </div>
        </div>
    </div>
@endsection