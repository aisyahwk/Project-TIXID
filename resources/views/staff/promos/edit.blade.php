@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto mt-3 p-4">
        @if (Session::get('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        <h5 class="text-center mb-3">Edit Diskon</h5>
        <form method="POST" action="{{ route('staff.promos.update', $promo->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" name="promo_code" id="promo_code"
                    class="form-control @error('promo_code') is-invalid @enderror"
                    value="{{ old('promo_code', $promo->promo_code)}}">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipe Promo</label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                    value="{{old('type', $promo->type)}}">
                    <option value="">Pilih</option>
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                    <option value="rupiah" {{ old('type') == 'rupiah' ? 'selected' : '' }}>Rupiah (Rp)</option>
                </select>
                @error('type')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" name="discount" id="discount"
                    class="form-control @error('discount')is-invalid @enderror"
                    value="{{old('discount', $promo->discount)}}">
                @error('discount')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
@endsection
</div>
