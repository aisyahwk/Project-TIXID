@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error')}}</div>
        @endif
        <h5 class="text-center mb-3">Edit Data Staff</h5>
        <form method="POST" action="{{route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ $user->name }}">
                @error('name')
                    <small class="text-danger">{{ $message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ $user->email }}">
                @error('email')
                    <small class="text-danger">{{ $message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <small class="text-danger">{{ $message}}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
