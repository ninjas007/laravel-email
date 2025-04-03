@extends('layouts.app')

@section('css')
    <link href="{{ asset('theme') }}/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content-app')
    <form action="{{ url('messages') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-2">
            <div class="col-12 text-right">
                <a href="{{ url('messages') }}" class="btn btn-warning btn-sm text-white">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-title pt-2">Buat Pesan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 text-right">
                                Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control" required>

                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subject" class="col-sm-2 text-right">
                                Subject <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="subject" class="form-control" required>

                                @error('subject')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="list" class="col-sm-2 text-right">
                                List Kontak <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="list_id" id="list_id" class="select2 form-control" required>
                                    @foreach ($lists as $list)
                                        <option value="{{ $list->id }}">{{ $list->name }}</option>
                                    @endforeach
                                </select>

                                @error('list_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-1">
                                <a href="{{ url('lists') }}" target="_blank" class="btn btn-primary btn-sm"
                                    title="Buat List">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="templateId" class="col-sm-2 text-right">
                                Template <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select name="template_id" id="templateId" class="select2-single form-control" required>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>

                                @error('template_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-1">
                                <a href="{{ url('templates/create') }}" target="_blank" class="btn btn-primary btn-sm"
                                    title="Buat Template">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
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
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih Data'
            });

            $('.select2-single').select2({
                placeholder: 'Pilih Data',
                template: 'bootstrap-4',
                width: '100%',
            })
        });
    </script>
@endsection
