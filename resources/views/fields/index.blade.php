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
    <div class="row mb-2">
        <div class="col-12 text-right">
            <a href="{{ url('fields/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Buat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Field</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center" width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fields as $key => $field)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $field->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('fields/' . encodeId($field->id) . '/edit') }}" title="Edit">
                                                <i class="mx-2 fa fa-pencil text-primary fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="deleteField(`{{ encodeId($field->id) }}`)"
                                                title="Hapus">
                                                <i class="fa fa-trash text-danger fs18"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($fields->count() > 0)
                            <div class="d-flex justify-content-end">
                                {{ $fields->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteField(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('fields') }}" + '/' + id,
                        type: "POST",
                        data: {
                            _method: 'DELETE',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            })
        }
    </script>
@endsection
