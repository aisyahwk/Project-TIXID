@extends('templates.app')

@section('content')
    <form action="{{route('staff.schedules.update', $schedule['id']) }}" class="container my-5" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="cinema_id" class="form-label">Bioskop</label>
            <input type="text" name="cinema_id" id="cinema_id" value="{{ $schedule['cinema']['name']}}"
            class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label for="movie_id" class="form-label">Film</label>
            <input type="text" name="movie_id" id="movie_id" value="{{ $schedule['movie']['title']}}"
            class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" name="price" id="price" value="{{ $schedule['price']}}" class="form-control
            @error('price') is-invalid @enderror">
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="hours" class="form-label">Jam tayang</label>
            {{--munculkan inpur sejumlah data array--}}
            @foreach ($schedule['hours'] as $index => $hours)
                <div class="d-flex align-items-center hour-item">
                    <input type="time" name="hours[]" id="hours" value="{{ $hours }}" class="form-control">
                    @if ($index > 0)
                    <i class="fa-solid fa-circle-xmark text-danger ms-2" style="font-size: 1.5rem; cursor:
                    pointer" onclick="this.closest('.hour-item').remove()"></i>
                    @endif
                    {{-- this.closest(.hour-item) : mencari item dengan class hour-item terdekat
                    (diatas componen yang di click (i) berarti div class hour-item nya--}}
                    {{--remove--}}
                </div>
            @endforeach
            <div id="additionalInput"></div>
            <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+ Tambah input jam</span>
            @if ($errors->has('hours.*'))
                <small class="text-danger">{{ $errors->first('hours.*')}}</small>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
@endsection

@push('script')
    <script>
        function addInput() {
            let content = `<div class="d-flex align-items-center hour-additional">
                        <input type="time" name="hours[]" id="hours" class="form-control">
                        <i class="fa-solid fa-circle-xmark text-danger ms-2" style="font-size: 1.5rem; cursor:
                        pointer" onclick="this.closest('.hour-additional').remove()"></i>
                        </div>`;
            let wrap = document.querySelector('#additionalInput');
            wrap.innerHTML += content;
        }
    </script>
@endpush
