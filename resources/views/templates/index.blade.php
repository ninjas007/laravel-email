@extends('layouts.app')

@section('content-app')
    <div class="row mb-2">
        <div class="col-12 text-right">
            <a href="{{ url('templates/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Buat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Template</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Terakhir diupdate</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($templates as $key => $list)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->category }}</td>
                                        <td>{{ $list->updated_at->format('d M Y H:i:s') }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('templates/' . encodeId($list->id)) }}" title="Detail">
                                                <i class="fa fa-eye text-info fs18"></i>
                                            </a>
                                            <a href="{{ url('templates/' . encodeId($list->id) . '/edit') }}"
                                                title="Edit">
                                                <i class="mx-2 fa fa-pencil text-primary fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="deleteTemplate(`{{ encodeId($list->id) }}`)" title="Hapus">
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

                        @if ($templates->count() > 0)
                            <div class="d-flex justify-content-end">
                                {{ $templates->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        function deleteTemplate(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('templates') }}" + '/' + id,
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
                            }).then(() => {
                                location.reload();
                            })
                        }
                    })
                }
            });
        }
    </script>
@endsection
