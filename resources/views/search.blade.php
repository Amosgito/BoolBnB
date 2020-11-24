@extends('layouts.main-layout')
@section('import')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>
<script src="{{ asset('/js/partials/search.js')}} "></script> 
@endsection
@section('content')

{{-- il template di handlebar va in conflitto mettendo {{ }} con blade. Per questo l'ho messo a parte in un file con estensione .php e non .blade.php --}}
@include('partials.handlebar-template')


<div class="container search margintop">
  <div class="filters">
    <div class="col-md-8 offset-md-2">
        <div class="input-group md-form form-sm form-2 pl-0">
            <input id="search" class="form-control my-0 py-1 rounded-pill" type="search" placeholder="Search" aria-label="Search" required min="3" value="{{ $data['address']}}">
            <div class="d-none">
              <span id="latitude" data-number="{{ $data['lat']}}"></span>
              <span id="longitude" data-number="{{ $data['lon']}}"></span>
            </div>
        </div>
    </div>
    <div class="col-md-8 offset-md-2 clearfix py-3">
      <button class="btn btn-link py-0" data-toggle="collapse" data-target="#adSearch" aria-expanded="true" aria-controls="collapseOne">
        Advaced Search
      </button>
      <div id="adSearch" class="collapse hide" aria-labelledby="headingOne">
        <div class="col-md-12 p-3">
          <h5>Distance</h5>
          <div class="form-group">
            <input type="range" class="form-control-range" id="distance" min="20" max="200" value="20">
            <span class="valueSpan text-primary float-right"></span>
          </div>
        </div>
        <div class="col-md-12 p-3">
          <h5>Services</h5>
          @foreach ($srvs as $srv)
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="service[]" id="{{ $srv -> name }}" value="{{ $srv -> id}}">
            <label class="form-check-label" for="{{ $srv -> name }}"><i class="px-1 {{ $srv -> icon }} "></i> {{ $srv -> name }}</label>
          </div>     
          @endforeach
        </div>
        <div class="col-md-12 p-3">
          <h5>Features</h5>
          <form class="form-inline">
            <label class="my-1 mr-2" >Rooms</label>
            <select class="custom-select my-1 mr-sm-2" id="rooms">
              @for ($i = 1; $i < 11; $i++)
                <option value="{{ $i }}">{{ $i }}</option> 
              @endfor
            </select>
            <div class="mx-2"></div>
            <label class="my-1 mr-2" >Beds</label>
            <select class="custom-select my-1 mr-sm-2" id="beds">
              @for ($i = 1; $i < 21; $i++)
                <option value="{{ $i }}">{{ $i }}</option> 
              @endfor
            </select>
          </form>
        </div>
      </div>
      <button type="button" id="search-button" class="btn btn-secondary btn-sm font-weight-bold rounded-pill float-right">Search</button>
    </div>
  </div>
  <div class="row rounded" id="sponsored">
    
  </div>
  <div class="row rounded" id="standard">

  </div>
</div>

@endsection 