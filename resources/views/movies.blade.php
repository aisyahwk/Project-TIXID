@extends('templates.app')

@section('content')
    <div class="container my-3">
        <h5 class="mb-3">Seluruh Film Sedang Tayang</h5>
        {{-- form untuk search : method="GET" karna akan menampilkan data bukan menambah data,
        action="" karna diproses --}}
        <form action="" method="GET">
            <div class="row">
                <div class="col-10">
                    <input type="text" class="form-control" placeholder="Cari judul film..." name="search_movie">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-center flex-wrap gap-2 my-3">
            @foreach ($movies as $item)
                <div class="card" style="width: 15rem; margin: 5px">
                    <img src="{{ asset('storage/' . $item['poster']) }}" class="card-img-top" alt="Sunset Over the Sea"
                        style="object-fit: cover; height: 350px" />
                    <div class="card-body" style="padding: 0 !important">
                        <p class="card-text text-center bg-primary py-2"><a href="{{ route('schedules.detail', $item['id']) }}"
                                class="text-warning"><b>Beli Tiket</b></a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
