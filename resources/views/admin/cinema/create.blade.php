@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error')}}</div>
        @endif
        <h5 class="text-center mb-3">Tambah Data Bioskop</h5>
        <form method="POST" action="{{route('admin.cinemas.store')}}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name')}}">
                @error('name')
                    <small class="text-danger">{{ $message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                <textarea name="location" id="location" rows="5"
                    class="form-control @error('location') is-invalid @enderror">{{ old('location')}}</textarea>
                <!--old di textarea ditulis-->
                @error('location')
                    <small class="text-danger">{{ $message}}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim Data</button>
        </form>
    </div>
@endsection
