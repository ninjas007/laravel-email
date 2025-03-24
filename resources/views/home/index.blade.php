{{-- home with logout --}}
@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total List</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="totalList">{{ $totalList ?? 0 }}</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-list"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Kontak</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="totalKontak">{{ $totalKontak ?? 0 }}</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-user"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Broadcast</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="totalBroadcast">{{ $totalBroadcast ?? 0 }}</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-envelope"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-4">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Template</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white" id="totalTemplate">{{ $totalTemplate ?? 0 }}</h2>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-briefcase"></i></span>
                </div>
            </div>
        </div>
    </div>
@endsection
