@extends('layouts.app')

@section('css')
    <link href="{{ asset('theme') }}/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow,
        .select2-container .select2-selection--single {
            height: 50px !important;
        }
    </style>
@endsection

@section('content-app')
    <form action="{{ url('contacts') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-2">
            <div class="col-12 text-right">
                <a href="{{ url('contacts') }}" class="btn btn-warning btn-sm text-white">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-title pt-2">Buat Kontak</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="form-group">
                            <label for="">Nama</label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label> <span class="text-danger">*</span>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">No Telepon</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Kontak List</label> <span class="text-danger">*</span>
                            <select name="contact_list[]" class="form-control select2" multiple required>
                                @foreach ($contactList as $list)
                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-title pt-2">Kolom Tambahan</h3>
                    </div>
                    <div class="card-body py-0">
                        <div class="form-group">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Kolom</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody id="customFields">
                                    @foreach ($fields as $field)
                                        <tr>
                                            <td>
                                                {{ $field->name }}
                                                <input type="hidden" name="custom_fields[name][]" value="{{ $field->id }}">
                                            </td>
                                            <td>
                                                <input type="text" name="custom_fields[value][]" class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('theme') }}/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
