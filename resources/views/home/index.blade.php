{{-- home with logout --}}
@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Signage</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="signageCount">1</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-list"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Player</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="playerCount">2</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-desktop"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Player Live</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="playerLiveCount">3</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-desktop"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-4">
                <div class="card-body">
                    <h3 class="card-title text-white">Storage Usage</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="storageUsage">4</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-floppy-o"></i></span>
                </div>
            </div>
        </div>
    </div>
@endsection
