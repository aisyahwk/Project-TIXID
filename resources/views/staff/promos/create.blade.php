@extends('templates.app')

@section('content')
    <div class="w-75 d-bloc mx-auto mt-3 p-4">
        <h5 class="text-center mb-3">Buat Data Staff</h5>
        <form method="POST" action="{{ route('staff.promos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" name="promo_code" id="promo_code" class="form-control">
                @error('promo_code')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Diskon</label>
                <select name="type" id="type" class="form-control">
                    <option value="percent">Persen (%)</option>
                    <option value="rupiah">Rupiah (Rp)</option>
                </select>
                @error('type')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" name="discount" id="discount" class="form-control">
                @error('discount')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

    </div>
@endsection
