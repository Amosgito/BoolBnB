@extends('layouts.main-layout')

@section('import')
<script src="{{ asset('/js/partials/chart.js') }}"></script>
@endsection

@section('content')



<div id="stats" data-id="{{ $id }}" class="container margintop">
    <div class="row">
        <h2 class="title">Statistics</h2>
    </div>
    <div calss="row">
        <div class="col-md-4 offset-md-5">
            <h4>Visits</h4>
        </div>
        <canvas id="myVisitsChart"></canvas>
    </div>
    <div calss="row">
        <div class="col-md-4 offset-md-5">
            <h4>Messages</h4>
        </div>
        <canvas id="myMessagesChart"></canvas>
    </div>
    <a class="btn btn-secondary float-right" href="{{ URL::previous() }}">Go Back</a>
</div>

@endsection