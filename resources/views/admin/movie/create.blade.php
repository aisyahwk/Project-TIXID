@extends('templates.app')

@section('content')
    <div class="w-75 mx-auto my-5">
        <form action="{{route('admin.movies.store', )}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-6">
                    <label for="title" class="form-label">Judul Film</label>
                    <input type="text" name="title" id="title" class="form-control
                            @error('title')
                                is-invalid
                            @enderror">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="duration" class="form-label">Durasi Film</label>
                    <input type="time" name="duration" id="duration" class="form-control
                            @error('duration')
                                is-invalid
                            @enderror">
                    @error('duration')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="genre" class="form-label">Genre Film</label>
                    <input type="text" name="genre" id="genre" placeholder="Romantic, Action, etc..." class="form-control
                            @error('genre')
                                is-invalid
                            @enderror">
                    @error('genre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="director" class="form-label">Sutradara Film</label>
                    <input type="text" name="director" id="director" class="form-control
                            @error('director')
                                is-invalid
                            @enderror">
                    @error('director')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_rating" class="form-label">Usia Minimal</label>
                    <input type="number" name="age_rating" id="age_rating" class="form-control
                            @error('age_rating')
                                is-invalid
                            @enderror">
                    @error('age_rating')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="poster" class="form-label">Poster Film</label>
                    <input type="file" name="poster" id="poster" class="form-control
                            @error('poster')
                                is-invalid
                            @enderror">
                    @error('poster')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Sinopsis Film</label>
                <textarea name="description" id="description" rows="5" class="form-control
                            @error('description')
                                is-invalid
                            @enderror">
                        </textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim data</button>
        </form>
    </div>
@endsection
