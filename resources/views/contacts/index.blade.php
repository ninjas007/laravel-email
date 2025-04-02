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
            <button class="btn btn-success btn-sm text-white" data-toggle="modal" data-target="#modalUpload">
                <i class="fa fa-upload"></i> Upload
            </button>
            <a href="{{ url('contacts/create') }}" class="btn btn-primary btn-sm" >
                <i class="fa fa-plus"></i> Buat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Daftar Kontak</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Hp</th>
                                    <th class="text-center" width="8%">Aksi</th>
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
                                            <a href="{{ url('contacts/' . encodeId($contact->id) . '/edit') }}"  title="Edit">
                                                <i class="mx-2 fa fa-pencil text-primary fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="deleteContact(`{{ encodeId($contact->id) }}`)" title="Hapus">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadLabel">Unggah Kontak</h5>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <a href="template-contact.csv" download="template-contact.csv"
                            class="btn btn-primary btn-sm text-white"><i class="fa fa-download"></i> Unduh Template
                            CSV</a>
                        <br>
                        <small style="font-size: 12px; color: #999; font-style: italic">
                            Anda juga bisa menambahkan kolom tambahan yang diperlukan
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">Unggah File</label> <span class="text-danger">*</span>
                        <input type="file" class="form-control" name="file_upload" id="fileUpload"
                            placeholder="Masukkan nama" required accept=".csv">
                    </div>
                    <div class="form-group">
                        <label for="contactList">Kontak List</label> <span class="text-danger">*</span>
                        <br>
                        <select name="contactList" id="contactList" class="form-control select2" multiple required
                            style="width: 100%">
                            @foreach ($contactList as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveUpload()">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Buat Kontak</h5>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createMediaForm">
                        <input type="hidden" id="contactId">
                        <div class="form-group mb-3">
                            <label for="name">Nama</label> <span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label> <span class="text-danger">*</span>
                            <input type="text" class="form-control" name="email" id="email"
                                placeholder="Masukkan email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">No Hp</label>
                            <input type="text" class="form-control" name="no_hp" id="phone"
                                placeholder="Masukkan no hp" required>
                        </div>
                        <div class="form-group">
                            <label for="contactListOne">Kontak List</label> <span class="text-danger">*</span>
                            <br>
                            <select name="contact_list_one" id="contactListOne" class="form-control select2" multiple
                                required style="width: 100%">
                                @foreach ($contactList as $list)
                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customFields">Custom Field</label>
                            <br>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Value</th>
                                        <th width="5%">
                                            <button type="button" class="btn btn-sm" onclick="addCustomField()">
                                                <i class="fa fa-plus fs18"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="customFields"></tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveContact()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('theme') }}/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactList').select2({
                placeholder: 'Select Contact List',
                allowClear: true,
            });

            $('#contactListOne').select2({
                placeholder: 'Select Contact List',
                allowClear: true,
            });

            // close modal and reset form
            $('#modalCreate').on('hidden.bs.modal', function() {
                $('#createMediaForm').trigger('reset');
                $('#contactId').val('');
                $('#contactListOne').val(null).trigger('change');
            });
        });

        async function deleteContact(id) {
            let url = "{{ url('contacts') }}/" + id;
            await deleteData(url);
        }

        async function saveContact() {
            let formData = new FormData();
            let method = 'POST';
            let url = "{{ route('contacts.store') }}";
            let contactList = $('#contactListOne').val();

            formData.append('name', $('#name').val());
            formData.append('email', $('#email').val());
            formData.append('phone', $('#phone').val());

            if (Array.isArray(contactList)) {
                contactList.forEach((item, index) => {
                    formData.append(`contact_list[${index}]`, item);
                });
            }

            if ($('#contactId').val()) {
                formData.append('id', $('#contactId').val());
                formData.append('_method', 'PUT');
                url = "{{ url('contacts') }}/" + $('#contactId').val();
            }

            await submitFormData(formData, url, method);
        }

        async function editContact(id) {
            getData(`{{ url('contacts') }}/${id}`)
                .then((res) => {
                    data = res.data;
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#contactId').val(data.id);

                    let contactListIds = JSON.parse(data.contact_list_id) || [];

                    $('#contactListOne').val(contactListIds).trigger('change');

                    $('#modalCreate').modal('show');
                })
                .catch((err) => {
                    console.log(err);
                })
        }

        async function processFile(filePath, offset = 0) {
            try {
                const response = await $.ajax({
                    url: "{{ url('contacts/process-file') }}",
                    type: "POST",
                    data: {
                        file_path: filePath,
                        contact_list: $("#contactList").val(),
                        offset: offset
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                if (response.status === "success") {
                    swal({
                        title: "Processing...",
                        text: `${response.inserted} data uploaded...`,
                        icon: "info",
                        buttons: false,
                    });

                    if (response.next_offset !== null) {
                        // Panggil lagi untuk batch berikutnya
                        setTimeout(() => processFile(filePath, response.next_offset), 500);
                    } else {
                        swal("Success!", "Semua data telah diupload.", "success");
                    }
                }
            } catch (error) {
                swal("Error!", "Failed to process file.", "error");
            }
        }

        async function saveUpload() {
            const fileData = new FormData();
            fileData.append("file_upload", $("#fileUpload")[0].files[0]);

            try {
                const uploadResponse = await $.ajax({
                    url: "{{ url('contacts/upload') }}",
                    type: "POST",
                    data: fileData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                if (uploadResponse.status === "success") {
                    processFile(uploadResponse.file_path, 0);
                }
            } catch (error) {
                swal("Error!", "Gagal mengupload file. Silahkan hubungi admin", "error");
            }
        };

        function addCustomField() {
            let tbody = `<tr>
                <td>
                    <input type="text" name="custom_fields[name][]" class="form-control" placeholder="Contoh: Alamat">
                </td>
                <td>
                    <input type="text" name="custom_fields[value][]" class="form-control" placeholder="Contoh: Jakarta">
                </td>
            </tr>`

            $('#customFields').append(tbody);
        }
    </script>
@endsection
