@extends('templates.index')

@section('content')
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-5">Tambah</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th class="text-center" width="100">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_sent ? 'Terkirim' : 'Belum dikirim' }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="ml-2">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <button class="btn btn-danger btn-icon" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-times"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No data</td>
                </tr>
            @endforelse
    </table>
@endsection

@push('js')

@endpush
