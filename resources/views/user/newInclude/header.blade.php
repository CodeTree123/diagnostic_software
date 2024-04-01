<div class=" p-3 border-bottom">
    <div class="container">
        <div class="row no-gutters d-flex align-items-start align-items-center px-3 px-md-0">
            <div class="col-md-2 " style="margin-left: 25px;">
                <div class="pr-md-4 pl-md-0 pl-3 text">
                    @if(!$shop)
                    <img style="width:140px" src="{{ asset('newAssets/images/doc-1.jpg') }}" alt="">
                    @else
                    <img style="width:140px" src="{{ asset($shop->logo) }}" alt="">
                    @endif
                </div>
            </div>
            @if(!$shop)
            <div class="col-md-8 ">
                <a class="navbar-brand" href="index.html" style="color: rgb(12, 12, 87);">Your organization Name</a><br>
                <small>Your organization Address</small>
            </div>
            @else
            <div class="col-md-8 ">
                <a class="navbar-brand" href="index.html" style="color: rgb(12, 12, 87);">{{$shop->name}}</a><br>
                <small>{{$shop->address}}</small>
            </div>
            @endif
            <div class="col-md-4 ">
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container d-flex align-items-center">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav m-auto">
                <li class="nav-item active"><a href="{{route('user.new.dashboard')}}" class="nav-link text-white"><span>Home</span></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><span class="background-color">New Entry</span></a></li>
                <li class="nav-item"><a href="#" class="nav-link">
                        <div class="background-color">Old Entry</div>
                    </a></li>
                <li class="nav-item"><a href="#" class="nav-link">
                        <div class="background-color">Page Setup</div>
                    </a></li>
                <li class="nav-item"><a href="#" class="nav-link">
                        <div class="background-color">Calculation</div>
                    </a></li>
                <li class="nav-item"><a href="#" class="nav-link">
                        <div class="background-color">Admin</div>
                    </a></li>
                <li class="nav-item"><a href="{{ route('ticket.open') }}" class="nav-link">
                        <div class="background-color">Contact With Us</div>
                    </a></li>
            </ul>
        </div>
    </div>
</nav>