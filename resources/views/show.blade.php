@extends('layouts.main-layout')

@section('import')
<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
<script src="{{ asset('/js/partials/map.js')}}"></script>
@endsection

@section('content')
<div class="container">
    <div class="show margintop">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <h1>{{ $apt -> title }}</h1>
        <h3>{{ $apt -> address }}</h3>
        <div class="col-md-12">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                @if (count($imgs))
                    @foreach($imgs as $img)
                    <div class="carousel-item
                        @if($loop -> first)
                        active
                        @endif
                    ">
                        <img class="d-block w-80 mx-auto" src="{{ $img -> img }}" alt="">
                    </div>
                    @endforeach
                @else
                <div class="carousel-item active">
                    <img class="d-block w-80 mx-auto" src="/img/image-not-found.png" alt="">
                </div>
                @endif
              </div>
              <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="description">
                    <h5>Description</h5>
                    <p> {{ $apt -> description }}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="services">
                    <h5>Services</h5>
                    <ul>
                        @foreach ($apt -> services as $srv)

                        <li><i class="px-1 {{ $srv -> icon }} "></i>  {{ $srv -> name }}</li>

                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-3">
                <div class="features">
                    <h5>Features</h5>
                    <ul>
                        <li> <i class="px-2 fas fa-bed"></i>Beds: {{ $apt -> bed_qt }} </li>
                        <li> <i class="px-2 fas fa-shower"></i>Bathrooms: {{ $apt -> bathroom_qt }} </li>
                        <li> <i class="px-2 fas fa-ruler-combined"></i>Measurement: {{ $apt -> mq }} sm </li>
                        <li> <i class="px-2 fas fa-couch"></i>Rooms: {{ $apt -> room_qt }} </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-md-6 ">
                <div class="show-map ">
                    <div id='map' class="rounded"></div>
                </div>
            </div>

            <div style="display: none;">
                <span id="lat-secrt" data-number="{{ $apt -> latitude }}"></span>
                <span id="log-secrt" data-number="{{ $apt -> longitude }}"></span>
            </div>

              <div class="col-xs-12 col-md-6 card">
                <div class="message">
                    <h5>Contact Host</h5>
                    <form action="{{ route('apt-storemsg', $apt -> id) }}" method="post">
                    @csrf
                    @method ('post')

                        <div class="form-group">
                            {{-- se l'utente è loggato viene impostata di default la sua mail --}}
                            @auth
                                <label for="exampleFormControlInput1">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Enter your email address" name="email" value="{{ Auth::user()->email }}" required>
                            @else
                                <label for="exampleFormControlInput1">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Enter your email address" name="email" required >
                            @endauth
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="exampleFormControlTextarea1" rows="3" name="message" minlength="3" maxlength="1000" required></textarea>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-secondary font-weight-bold rounded-pill" href="">Send</button>
                    </form>
                  </div>
                </div>

        </div>
            <a class="btn btn-primary float-right mt-3 btnContact" href="{{ URL::previous() }}">Go Back</a>
    </div>
</div>


@endsection
