@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{route('staff.schedules.index')}}" class="btn btn-secondary">Kembali</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">{{session::get('success')}}</div>
        @endif
        <h3 class="my-3">Data SAMPAH : Jadwal Tayangan</h3>
        <table class="table table-bordered">
            <tr>
                <td>#</td>
                <td>Nama Bioskop</td>
                <td>Judul film</td>
                <td>Aksi</td>
            </tr>
            @foreach ($schedules as $key => $schedule)
            <tr>
                <td>{{ $key+1}}</td>
                <td>{{ $schedule['cinema']['name'] }}</td>
                <td>{{ $schedule['movie']['title']}}</td>
                <td class="d-flex align-items-center">
                    <form action="{{route('staff.schedules.restore', $schedule['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Kembalikan</button>
                    </form>
                    <form action="{{route('staff.schedules.delete_permanent', $schedule['id']) }}" method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Selamanya</button>
                    </form>
                </td>
            </tr>
          @endforeach
        </table>
    </div>
@endsection
