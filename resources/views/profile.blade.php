@extends('layouts.main-layout')

@section('import')
<script src="{{ asset('/js/partials/profile.js')}} "></script> 
@endsection

@section('content')

<div class="container">
  <div class="profile margintop">
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="row">
        <div class="col-12 text-center p-3">
            <a class="btn btn-secondary rounded-pill font-weight-bold" href="{{route('apt-create') }}">Add Apartment</a>
        </div>
    </div>

    @foreach ($apts as $apt)
      <div class="row border rounded m-3 p-2 shadow bg-white">
        <div class="col-md-8 px-1">

          <div class="media align-items-stretch p-1" >
            @if (count($apt -> images))
            <a href="{{ route('apt-show', $apt -> id) }}">
              <div class="image-faker" style="background-image: url('{{ $apt -> images -> first -> img -> img }}')">
              </div>
            </a>
            @else
            <a href="{{ route('apt-show', $apt -> id) }}">
              <div class="image-faker" style="background-image: url('{{ asset('img/image-not-found.png') }}')">
              </div>
            </a>
            @endif

            <div class="media-body pl-2 d-flex flex-column justify-content-between">
              <a href=" {{ route('apt-show', $apt -> id) }} ">
                  <h3 class="mt-0 mb-1 mr-2 text-capitalize"> {{ $apt -> title }} </h3>
                  <div class="txt mr-3 font-italic">
                        {{ $apt -> address }}
                  </div>
              </a>
              <div class="sponsorship font-weight-bold">
                @if ($apt['sponsored'])
                    This apartment is sponsored until: {{ $apt['sponsored'] }}
                @endif
              </div>
            </div>

          </div>

        </div>

        <div class="col-md-4">
          <div class="btn-wrapper h-100 d-flex flex-column justify-content-around align-items-center">

            <div class="custom-control custom-switch">
                <form action="">

                  <input 
                  @if ($apt -> visible)
                      checked
                  @endif

                  type="checkbox" class="custom-control-input visibilitySwitch"
                  data-usrId="{{ $apt -> user_id }}" data-aptId="{{ $apt -> id }}" id="visibilitySwitch{{ $apt -> id }}" >
                  <label class="custom-control-label" for="visibilitySwitch{{ $apt -> id }}">Set Visibility</label>
                </form>
            </div>
            <div>
                <a href=" {{ route('apt-stats', $apt -> id) }}" class="btn btn-primary btn-sm m-1 btn-link">Stats</a>
                <a href=" {{ route('apt-promotion', $apt -> id) }}" class="btn btn-primary btn-sm m-1 btn-link sponsor-button" data-sponsor="{{ $apt['sponsored'] }}">Sponsor</a>
                <a href=" {{ route('apt-edit', $apt -> id) }}" class="btn btn-primary btn-sm m-1 btn-link">Edit</a>
                <button type="button" data-id="{{ $apt -> id }}" data-title=" {{ $apt -> title }} " data-toggle="modal" data-target="#deleteModal" class="delete-btn btn btn-danger btn-sm m-1">DELETE</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
<!-- Modale di conferma per il delete dell'appartamento -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to permanently delete apartment <strong><span id="apt-title"></span></strong>?</p>
      </div>
      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <a type="button" id="confirm-delete-btn" class="btn btn-danger">DELETE</a>
      </div>
    </div>
  </div>
</div>

@endsection

