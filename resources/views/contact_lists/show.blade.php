@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="{{ url('lists') }}" class="btn btn-sm btn-warning text-white my-2">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-title pt-2">Kontak List {{ $contactList->name }}</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Hp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts as $key => $contact)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
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
@endsection

@section('js')
    <script></script>
@endsection
