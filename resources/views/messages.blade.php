@extends('layouts.main-layout')
@section('import')
<script src="{{ asset('/js/partials/messages.js')}} "></script> 
@endsection
@section('content')
<div id="messages">
    <div class="container margintop">
        @if (count($msgs))
            @foreach ($msgs as $msg)
                <div class="row media border shadow bg-white 
                @if(!$msg -> read)
                border-secondary
                @endif
                rounded m-2 p-2">
                    <div class="col-md-3">
                        <div class="py-1">
                            <label>Email: </label>
                            <a href="mailto:{{ $msg -> email }}" class="m-0"><h6 class="m-0">{{ $msg -> email }} <span class="little-text">(Click here to reply)</span></h6></a>
                        </div>
                        <div class="py-1">
                            <label>Apartment: </label> <br>
                            <a href=" {{ route('apt-show', $msg -> apartment_id) }} " class="mt-0"><h6 class="m-0">{{ $msg -> title }}</h6></a>
                        </div>
                        <div class="py-1">
                            <label>Date: </label>
                            <p class="text-muted date">{{ $msg -> created_at }}</p>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <label>Message: </label>
                        <p>{{ $msg -> message }}</p>
                    </div>
                </div>
            @endforeach
        @else
        <h3 class="w-100 text-center py-5 text-primary">No Messages</h3>
        @endif
  </div>
</div>
@endsection
