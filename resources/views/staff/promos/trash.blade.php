@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{route('staff.promos.index')}}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data sampah : Data Promo</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{session::get('success')}}</div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Kode Promo</th>
                <th>Total Potongan</t>
                <th>Aksi</th>
            </tr>
            @foreach ($promoTrash as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $item->promo_code }}</td>
                <td>
                    @if($item->type == 'percent')
                    {{ $item->discount }}%
                    @else
                        Rp {{ number_format($item->discount, 0, ',', '.') }}
                    @endif
                </td>
                <td class="d-flex allign-items-center">
                    <form action="{{route('staff.promos.restore', $item['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Kembalikan</button>
                    </form>
                    <form action="{{route('staff.promos.delete_permanent', $item['id']) }}" method="POST" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Selamanya</button>
                    </form>
                </td>
            </tr>

            @endforeach
@endsection
