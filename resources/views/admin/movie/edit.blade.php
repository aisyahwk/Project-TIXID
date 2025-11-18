@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- karena edit/update --}}

            <div class="row mb-3">
                <div class="col-6">
                    <label for="title" class="fomr-label">Judul Film</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $movie->title) }}">
                </div>
                <div class="col-6">
                    <label for="duration" class="fomr-label">Durasi Film</label>
                    <input type="time" name="duration" id="duration"
                        class="form-control @error('duration') is-invalid @enderror"
                        value="{{ old('duration', $movie->duration) }}">
                    @error('duration')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="genre" class="form-label">Genre Film</label>
                    <input type="text" name="genre" id="genre" placeholder="Romantis, Komedi"
                        class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre', $movie->genre) }}">
                    @error('genre')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="director" class="form-label">Sutradara</label>
                    <input type="text" name="director" id="director"
                        class="form-control @error('director') is-invalid @enderror"
                        value="{{ old('director', $movie->director) }}">
                    @error('director')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_rating" class="form-label">Usia Minimal</label>
                    <input type="number" name="age_rating" id="age_rating"
                        class="form-control @error('age_rating') is-invalid @enderror"
                        value="{{ old('age_rating', $movie->age_rating) }}">
                    @error('age_rating')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="poster" class="form-label">Poster Film</label>
                    <input type="file" name="poster" id="poster" class="form-control @error('poster') is-invalid @enderror">
                    @error('poster')
                        <small class="text-danger">{{$message}}</small>
                    @enderror

                    {{-- Tampilkan poster lama --}}
                    @if($movie->poster)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" width="120">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Sinopsis</label>
                <textarea name="description" id="description" rows="5"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $movie->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
