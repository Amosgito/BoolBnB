@extends('layouts.main-layout')
@section('import')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>
<script src="{{ asset('/js/partials/autocomplete.js')}} "></script>
@endsection

@section('content')

<div class="home margintop">
    <section id="hero">
        <div class="hero-container">
          <h1 class="font-weight-bold">Welcome to BoolBnB</h1>
          <h2>Find an apartment, become a Host.. or Both!</h2>
          <form class="w-100 px-3" action="{{ route('to-search') }}" method="GET">
            @csrf
            @method('GET')
              <div class="d-flex justify-content-center align-items-center">
                <div class="search-wrapper mr-3 input-group md-form form-sm form-2 pl-0 text-left">
                    <input id="search-address-input" name="address" class="form-control my-0 py-1 red-border rounded-pill" type="search" placeholder="Search" aria-label="Search" required min="3">
                    <div class="d-none">
                       <input id="latitude" name="lat" type="text">
                       <input id="longitude" name="lon" type="text">
                    </div>
                </div>
                <button type="submit"class="btn btn-secondary font-weight-bold rounded-pill border-white">Search</button>

              </div>

        </form>

        </div>
      </section><!-- #hero -->
      <div class="container">
        <div class="row justify-content-center align-items-stretch">
            <h2 class="font-weight-bold mb-3 col-md-12 mt-5 text-center">Our Suggestions</h2>
            @foreach ($apts as $apt)
            <div class="col-xs-12 col-sm-6 col-lg-4 align-items-stretch">
              <div class="card-wrapper p-2 h-100">
                <a class="text-decoration-none" href="{{ route('apt-show', $apt -> id) }}">
                    <div class="card rounded h-100 border">
                      <div class="img-wrapper px-3 pt-3">
                        <img class=" rounded" src="{{ $apt['img'] }}" alt="">
                      </div>
                      <div class="p-3">
                        <h4 class="card-title text-capitalize">{{ $apt -> title }}</h4>
                        <p class="card-text text-capitalize">{{ $apt -> address }}</p>
                      </div>
                    </div>
                </a>
              </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
