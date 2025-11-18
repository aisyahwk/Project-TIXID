@extends('templates.app')

@section('content')
    <div class="container my-5">
        @foreach ($cinemas as $cinema)
            <a href="{{ route('cinemas.schedules', $cinema->id) }}" class="card mb-3">
                <div class="card-body d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="fa-solid fa-star text-secondary"></h5>
                        <h5>{{ $cinema['name'] }}</h5>
                    </div>
                    <div>
                        <h5 class="fa-solid fa-arrow-right text-secondary"></h5>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
