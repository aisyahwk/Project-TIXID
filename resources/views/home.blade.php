@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        {{-- Auth::user() : mengambil data pengguna, Auth::user()->fieldtableusers --}}
        <div class="alert alert-success w-100">{{ Session::get('success') }}</div>
        <b>Selamat Datang, {{ Auth::user()->name}}</b></div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-warning">{{ Session::get('logout')}}</div>
    @endif
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center w-100" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-location-dot me-2"></i>BOGOR
        </button>
        <ul class="dropdown-menu w-100">
            <li><a class="dropdown-item" href="#">Bogor</a></li>
            <li><a class="dropdown-item" href="#">Jakarta</a></li>
            <li><a class="dropdown-item" href="#">Bandung</a></li>
        </ul>
    </div>

    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img src="https://asset.tix.id/microsite_v2/0458b98f-da57-40f2-ad28-30ced7a142a6.webp" class="d-block w-100"
                    alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img src="https://asset.tix.id/microsite_v2/8b69c88d-4514-4e07-87db-84c612a3f08a.webp" class="d-block w-100"
                    alt="Canyon at Nigh" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img src="https://asset.tix.id/microsite_v2/2e71513b-00b2-4c2f-ae86-e62abb3dd24e.webp" class="d-block w-100"
                    alt="Cliff Above a Stormy Sea" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->
    <div class="container my-3">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="mt-3">
                <h5>
                    <i class="fa-solid fa-clapperboard"></i> Sedang Tayang
                </h5>
            </div>
            <div>
                <a href="{{ route('home.movies.all')}}" class="btn btn-warning rounded-pill">
                    <i class="fa-solid fa-film"></i> Lihat Semua
                </a>
            </div>
        </div>
        <div class="d-flex my-3 gap-2">
            <a href="" class="btn btn-outline-primary rounded-pill" style="padding: 5px 10px !important"><small>Semua
                    Film</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>XXI</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>CGV</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>CINEPOLIS</small></a>
        </div>
    </div>
        <div class="d-flex justify-content-center gap-2 my-3">
        @foreach ($movies as $item)
        <div class="card" style="width: 13rem;">
            <img src="{{ asset('storage/' . $item['poster']) }}" class="card-img-top" alt="Sunset Over the Sea" style="object-fit: cover; height: 350px"/>
            <div class="card-body" style="padding: 0 !important">
                <p class="card-text text-center bg-primary py-2"><a href="{{ route('schedules.detail', $item['id']) }}"
                        class="text-warning"><b>Beli Tiket</b></a></p>
            </div>
        </div>
        @endforeach
    </div>
    </div>
@endsection
