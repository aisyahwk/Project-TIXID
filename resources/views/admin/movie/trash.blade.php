@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{route('admin.movies.index')}}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data sampah Film</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        <table class="table table-bordered">
            <tr>
                <td>#</td>
                <td>Poster</td>
                <td>Judul film</td>
                <td>Status</td>
                <td>Aksi</td>
            </tr>
            @foreach ($movies as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $item['poster']) }}" width="120">
                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td>
                        @if ($item['actived'] == 1)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Non Aktif</span>
                        @endif
                    </td>
                    <td class="d-flex align-items-center">
                        <form action="{{route('admin.movies.restore', $item['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Kembalikan</button>
                        </form>
                        <form class="ms-2" action="{{route('admin.movies.delete_permanent', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>

            @endforeach
        </table>
    </div>
@endsection
