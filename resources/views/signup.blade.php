@extends('templates.app')

@section('content')
    <form class="w-75 d-block mx-auto my-5" method="POST" action="{{route('signup.store')}}">

        <!-- 2 column grid layout with text inputs for the first and last names -->
        {{--csrf : token sebagai kunci agar data form bisa diakses server/controller --}}

        @csrf
        <div class="row mb-4">
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example1" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{old('first_name')}}" />
                    <label class="form-label" for="form3Example1">Nama Depan</label>
                </div>
                @error('first_name')
                {{--@error('name_input') " mengambil error validasi input tab--}}
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example2" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{old('last_name')}}" />
                    <label class="form-label" for="form3Example2">Nama Belakang</label>
                </div>
                @error('last_name')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
        </div>

        <!-- Email input -->
        @error('email')
            <small class="text-danger">{{$message}}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form3Example3" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" />
            <label class="form-label" for="form3Example3">Email</label>
        </div>

        <!-- Password input -->
        @error('password')
            <small class="text-danger">{{$message}}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="form3Example4" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}"/>
            <label class="form-label" for="form3Example4">Password</label>
        </div>

        <!-- Checkbox -->
        <div class="form-check d-flex justify-content-center mb-4">
            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33" checked />
            <label class="form-check-label" for="form2Example33">
                Subscribe to our newsletter
            </label>
        </div>

        <!-- Submit button -->
        <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>or sign up with:</p>
            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-facebook-f"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-google"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-twitter"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-github"></i>
            </button>
        </div>
    </form>
@endsection
