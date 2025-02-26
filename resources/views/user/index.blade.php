@extends('templates.index')

@section('content')
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-5">Tambah</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    
@endsection

@push('js')

@endpush
