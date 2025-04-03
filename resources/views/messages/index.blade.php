@extends('layouts.app')

@section('content-app')
    <div class="row mb-2">
        <div class="col-12 text-right">
            <a href="{{ url('messages/create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Buat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Pesan</h3>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Subject</th>
                                    <th>List</th>
                                    <th>Template</th>
                                    <th class="text-center" width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $key => $list)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->subject }}</td>
                                        <td>{{ $list->contact_list->name }}</td>
                                        <td>{{ $list->template->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('messages/' . encodeId($list->id)) }}/edit">
                                                <i class="mx-2 fa fa-pencil text-primary fs18"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                onclick="deleteData(`{{ url('messages') . '/' . encodeId($list->id) }}`)" >
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

                        @if ($messages->count() > 0)
                            <div class="d-flex justify-content-end">
                                {{ $messages->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
