@extends('layouts.app')

@section('css')
@endsection

@section('content-app')
    <form action="{{ url('fields') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-title pt-2">Buat Field</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama</label> <span class="text-danger">*</span>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="text-right">
                                <a href="{{ url('fields') }}" class="btn btn-warning text-white btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
@endsection
