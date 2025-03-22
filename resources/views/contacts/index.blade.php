@extends('layouts.app')

@section('content-app')
    <div class="row mb-2">
        <div class="col-12 text-right">
            <button class="btn btn-success btn-sm text-white" data-toggle="modal" data-target="#modalUpload">
                <i class="fa fa-upload"></i> Upload
            </button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCreate">
                <i class="fa fa-plus"></i> Buat
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Semua Kontak</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Handphone</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts as $key => $contact)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm"
                                                onclick="editContact(`{{ $contact->id }}`)"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm"
                                                onclick="deleteContact(`{{ $contact->id }}`)"><i class="fa fa-trash"></i>
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

                        @if ($contacts->count() > 0)
                            <div class="d-flex justify-content-end">
                                {{ $contacts->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUploadLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadLabel">Upload List Kontak</h5>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download Template XLS</button>
                    </div>
                    <div class="form-group">
                        <label for="name">Upload File</label><span class="text-danger">*</span>
                        <input type="file" class="form-control" name="name" id="name" placeholder="Masukkan nama"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveGroup()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Buat Kontak</h5>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createMediaForm">
                        <input type="hidden" id="groupId">
                        <div class="form-group mb-3">
                            <label for="name">Nama</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Email</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Telephone</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveGroup()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        async function deleteContact(id) {
            let url = "{{ url('contacts') }}/" + id;
            await deleteData(url);
        }

        async function saveContact() {
            let formData = new FormData();
            let method = 'POST';
            let url = "{{ route('contacts.store') }}";

            formData.append('name', $('#name').val());

            if ($('#groupId').val()) {
                formData.append('id', $('#groupId').val());
                formData.append('_method', 'PUT');
                url = "{{ url('contacts') }}/" + $('#groupId').val();
            }

            await submitFormData(formData, url, method);
        }

        async function editContact(id) {
            getData(`{{ url('contacts') }}/${id}`)
                .then((res) => {
                    let data = res.group;
                    $('#name').val(data.name);
                    $('#groupId').val(data.id);
                    $('#modalCreate').modal('show');
                })
                .catch((err) => {
                    console.log(err);
                })
        }
    </script>
@endsection
