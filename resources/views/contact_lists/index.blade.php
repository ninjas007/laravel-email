@extends('layouts.app')

@section('content-app')
    <div class="row mb-2">
        <div class="col-12 text-right">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCreate">
                <i class="fa fa-plus"></i> Buat
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">List</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Total Kontak</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contactLists as $key => $list)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ url('lists') }}/{{ encodeId($list->id) }}" class="text-primary font-weight-bold">{{ $list->name }}</a>
                                        </td>
                                        <td>{{ $list->total_contacts }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('lists.show', encodeId($list->id)) }}" title="Detail List">
                                                <i class="fa fa-users text-info fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)" title="Edit List" onclick="editList(`{{ encodeId($list->id) }}`)"><i
                                                    class="mx-2 fa fa-pencil text-primary fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)" title="Hapus List" onclick="deleteList(`{{ encodeId($list->id) }}`)"><i
                                                    class="fa fa-trash text-danger fs18"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($contactLists->count() > 0)
                            <div class="d-flex justify-content-end">
                                {{ $contactLists->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Form List</h5>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="contactListId">
                        <div class="form-group mb-3">
                            <label for="name">Nama</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveList()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#modalCreate').on('hidden.bs.modal', function() {
                $('#contactListId').val('');
                $('#name').val('');
            })
        });

        async function deleteList(id) {
            let url = "{{ url('lists') }}/" + id;
            await deleteData(url);
        }

        async function saveList() {
            let formData = new FormData();
            let method = 'POST';
            let url = "{{ route('lists.store') }}";

            formData.append('name', $('#name').val());

            if ($('#contactListId').val()) {
                formData.append('id', $('#contactListId').val());
                formData.append('_method', 'PUT');
                url = "{{ url('lists') }}/" + $('#contactListId').val();
            }

            await submitFormData(formData, url, method);
        }

        async function editList(id) {
            getData(`{{ url('lists') }}/${id}/edit`)
                .then((res) => {
                    let data = res.data;
                    $('#name').val(data.name);
                    $('#contactListId').val(data.id);
                    $('#modalCreate').modal('show');
                })
                .catch((err) => {
                    console.log(err);
                })
        }
    </script>
@endsection
