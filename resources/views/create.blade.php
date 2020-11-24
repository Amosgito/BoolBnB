@extends('layouts.main-layout')
@section('import')
<script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>
<script src="{{ asset('/js/partials/create.js')}} "></script>
@endsection
@section('content')

    <div class="bg_edit container margintop border rounded" style="background-image: url('{{ asset('/img/create_background.jpg')}}'); background-repeat: no-repeat; background-size: cover; background-position: center;">

      <div id="create-cont">
          <div class="form panel-transparent p-5 rounded w-100">

              <form action="{{ route('apt-store') }}" enctype="multipart/form-data" method="post" class="form-create-border">
                <h1>Create an Apartment</h1>

                  @csrf
                  @method ('post')

                  <div class="row font-weight-bold">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="">Title</label>
                          <input class="form-control  @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title') }}" minlength="3" maxlength="60" required>
                          <span class="validity text-muted">The title must be 3 - 60 character length</span>
                          @error('title')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                      <div class="form-group">
                          <label for="">Address</label>
                          <input class="form-control  @error('address') is-invalid @enderror" type="search" id="address-input" name="address" value="{{ old('address')}}" minlength="3" maxlength="230" required>
                          <span class="address validity text-muted">The address must have minimun 3 characters</span>
                          @error('address')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                      <div class="form-group d-none">
                          <input id="longitude" class="form-control" type="text" name="longitude" value="{{ old('longitude')}}">
                          <input id="latitude" class="form-control" type="text" name="latitude" value="{{ old('latitude')}}">
                      </div>

                      <div class="form-group">
                          <label for="">Room Quantity</label>
                          <input class="form-control  @error('room_qt') is-invalid @enderror" type="number" name="room_qt" value="{{ old('room_qt') }}" min="1" max="20" required>
                          <span class="validity text-muted">Accepted range of values: 1 - 20</span>
                          @error('room_qt')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                      <div class="form-group">
                          <label for="">Bed Quantity</label>
                          <input class="form-control  @error('bed_qt') is-invalid @enderror" type="number" name="bed_qt" value="{{ old('bed_qt') }}" min="1" max="50" required>
                          <span class="validity text-muted">Accepted range of values: 1 - 50</span>
                          @error('bed_qt')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                      <div class="form-group">
                          <label for="">Bathroom Quantity</label>
                          <input class="form-control  @error('bathroom_qt') is-invalid @enderror" type="number" name="bathroom_qt" value="{{ old('bathroom_qt') }}" min="1" max="8" required>
                          <span class="validity text-muted">Accepted range of values: 1 - 8</span>
                          @error('bathroom_qt')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                      <div class="form-group">
                          <label for="">Square Meters</label>
                          <input class="form-control  @error('mq') is-invalid @enderror" type="number" name="mq" value="{{ old('mq') }}" min="15" max="5000" required>
                          <span class="validity text-muted">Accepted range of values: 15 - 5000</span>
                          @error('mq')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>

                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-des form-control @error('description') is-invalid @enderror" name="description" cols="30" rows="10" minlength="3" maxlength="1000" required>{{ old('description') }}</textarea>
                        <span class="validity text-muted">The description must be 3 - 1000 character length</span>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>

                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input @error('img') is-invalid @enderror @error('img.*') is-invalid @enderror" id="inputGroupFile01 " aria-describedby="inputGroupFileAddon01" type="file" name="img[]" accept="image/*" multiple>
                          <label class="custom-file-label validity" for="inputGroupFile01">Max 5 files / 2 Mb</label>
                        </div>
                        @error('img')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('img.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>

                      @foreach ($srvs as $srv)
                      <div class="form-check">
                        <input type="checkbox" name="services[]" value="{{ $srv -> id }}">
                        <label for="{{ $srv -> name }}"><i class="px-1 {{ $srv -> icon}}"></i>  {{ $srv -> name }}</label>
                      </div>
                      @endforeach

                      <button id="create-submit" class="btn btn-secondary btn-save btnContact btn-block btn-sm mt-3" type="submit"><span class="glyphicon glyphicon-share"></span>Save</button>

                    </div>
                  </div>


              </form>
          </div>
      </div>
    </div>



@endsection
