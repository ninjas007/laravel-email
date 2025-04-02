@extends('layouts.app')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <style>
        .summernote-container {
            min-height: 500px;
        }

        .preview-content {
            border: 1px solid #b8b8b8;
            padding: 10px;
            min-height: 200px;
            background-color: #ffffff;
        }
    </style>
@endsection

@section('content-app')
    <div class="row" id="cardField">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body p-3">
                    <p>Masukkan field dengan format [[namafield]] (tanpa spasi)</p>
                    <p>Contoh: [[name]]</p>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('templates.update', encodeId($template->id)) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="row mb-2">
            <div class="col-12 text-right">
                <a href="{{ url('templates') }}" class="btn btn-warning text-white btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" id="btnSave" class="btn btn-primary text-white btn-sm">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Card Editor Summernote -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ $template->name }}">

                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" id="kategori" name="category" class="form-control"
                                value="{{ $template->category }}">

                            @error('category')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="backgroundColor">Warna Latar Belakang</label>
                            <input type="color" name="background_color" id="backgroundColor" class="form-control"
                                value="{{ $template->background_color }}">

                            @error('background_color')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="summernote">Konten</label>
                            <textarea id="summernote" class="summernote-container" name="summernote">{{ json_decode($template->body_template) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <h5>Hasil Template Pesan</h5>
                <div id="preview" class="preview-content"></div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {

            // preview
            $('#preview').html($('#summernote').summernote('code'));
            // background color
            const backgroundColor = $('#backgroundColor').val();
            $('#preview').css('background-color', backgroundColor);

            function closeCardField() {
                $('#cardField').css('display', 'none');
            }

            $('#summernote').summernote({
                height: 400,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'video']],
                    ['insert', ['customButton']],
                    ['view', ['codeview']]
                ],
                buttons: {
                    customButton: function(context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-square"></i> Button',
                            tooltip: 'Insert Custom Button',
                            click: function() {
                                Swal.fire({
                                    title: "Customize Button",
                                    html: htmlCustomButton(),
                                    showCancelButton: true,
                                    confirmButtonText: "Insert",
                                    cancelButtonText: "Cancel",
                                    confirmButtonColor: "#6C63FF",
                                    cancelButtonColor: "#6c757d",
                                    customClass: {
                                        popup: "small-modal"
                                    },
                                    preConfirm: () => {
                                        return {
                                            text: document.getElementById(
                                                "btn-text").value,
                                            link: document.getElementById(
                                                    "btn-link")
                                                .value, // Menyimpan link
                                            textColor: document.getElementById(
                                                "btn-text-color").value,
                                            bgColor: document.getElementById(
                                                "btn-bg-color").value
                                        };
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const buttonHTML = `
                                            <a href="${result.value.link}" target="_blank" style="text-decoration: none;">
                                                <button type="button" style="background-color: ${result.value.bgColor}; color: ${result.value.textColor}; padding: 8px 16px; border: none; border-radius: 5px; font-weight: bold;">
                                                    ${result.value.text}
                                                </button>
                                            </a>
                                        `;
                                        $('#summernote').summernote("pasteHTML",
                                            buttonHTML);
                                    }
                                });
                            }
                        });
                        return button.render();
                    }
                }
            });

            function uploadFile(file) {
                let data = new FormData();
                data.append('file', file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('messages/upload') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(url) {
                        $('#summernote').summernote("insertImage", url);
                    }
                });
            }

            function htmlCustomButton() {
                return `
                    <div style="display: flex; flex-direction: column; gap: 10px; align-items: start; width: 100%; margin: auto;">
                        <div style="width: 100%;">
                            <label style="font-size: 14px;">Text:</label>
                            <input id="btn-text" placeholder="Enter button text" value="Klik Saya" style="width: 100%; padding: 2px 6px; border: 1px solid #ccc; border-radius: 5px;">
                        </div>

                        <div style="width: 100%;">
                        <label style="font-size: 14px;">Link (URL):</label>
                        <input id="btn-link" placeholder="https://example.com" style="width: 100%; padding: 2px 6px; border: 1px solid #ccc; border-radius: 5px;" value="#">
                        </div>

                        <div style="width: 100%;">
                        <label style="font-size: 14px;">Text Color:</label>
                        <input type="color" id="btn-text-color" style="width: 100%; height: 20px; padding: 2px 5px; border: none; background: transparent; display: block; cursor: pointer;" value="#ffffff">
                        </div>

                        <div style="width: 100%;">
                        <label style="font-size: 14px;">Background Color:</label>
                        <input type="color" id="btn-bg-color" style="width: 100%; height: 20px; padding: 2px 5px; border: none; background: transparent; display: block; cursor: pointer;" value="#6C63FF">
                        </div>
                    </div>
                `;
            }

            $('#backgroundColor').on('input', function() {
                let color = $(this).val();
                $('#preview').css('background-color', color);
            });

            $('.note-editable').on('keyup', function() {
                let contents = $('#summernote').summernote('code');
                $('#preview').html(contents);
            });

            $('.note-btn[data-event="codeview"]').on('click', function() {
                setTimeout(() => {
                    let contents = $('#summernote').summernote('code');
                    $('#preview').html(contents);
                }, 100);
            });
        });
    </script>
@endsection
