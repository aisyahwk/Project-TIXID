@extends('templates.app')

@section('content')

    <div class="container my-5 card">
        <div class="card-body">
            {{-- karena data schedules diambil dengan get() dan data lebih dari satu, maka untuk mengambil data cinema nya
            ambil dari 1 data aja index 0--}}
            <i class="fa-solid fa-location-dot me-3"></i>{{ $schedules [0]['cinema']['location'] }}
            <hr>
            @foreach ($schedules as $schedule)
                <div class="my-2">
                    <div class="d-flex">
                        <div style="width: 150px; height: 200px">
                            <img src="{{asset('storage/' . $schedule['movie']['poster']) }}" class="w-100">
                        </div>
                        <div class="ms-5 mt-4">
                            <h5>{{ $schedule['movie']['title']}}</h5>
                            <table>
                                <tr>
                                    <td><b class="text-secondary">Genre</b></td>
                                    <td class="px-3"></td>
                                    <td>{{$schedule['movie']['genre']}}</td>
                                </tr>

                                <tr>
                                    <td><b class="text-secondary">Durasi</b></td>
                                    <td class="px-3"></td>
                                    <td>1{{$schedule['movie']['duration'] }}</td>
                                </tr>

                                <tr>
                                    <td><b class="text-secondary">Sutradara</b></td>
                                    <td class="px-3"></td>
                                    <td>{{$schedule['movie']['director']}}</td>
                                </tr>

                                <tr>
                                    <td><b class="text-secondary">Rating Usia</b></td>
                                    <td class="px-3"></td>
                                    <td><span class="badge badge-danger">{{$schedule['movie']['age_rating']}}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="w-100 my-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                            </div>
                        </div>
                        <div class="d-flex gap-3 ps-3 my-2">
                            @foreach ($schedule['hours'] as $index => $hours)
                                {{-- this : mengirimkan element html ini ke JS untuk di manipulasi --}}
                                <div class="btn btn-outline-secondary" style="cursor: pointer" onclick="selectedHour('{{ $schedule->id }}',
                                                                                                        '{{ $index }}', this)">
                                    {{$hours}}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
    </div>
    <div class="w-100 p-2 bg-light text-center fixed-bottom" id="wrapBtn">
        {{-- disabled button = kalau di a href (javascript:void(0)) --}}
        <a href="javascript:void(0)" id="btnTicket"><i class="fa-solid fa-ticket"></i>BELI TIKET</a>
    </div>
@endsection

@push('script')
    <script>
        //menyimpan element sebelumnya yang pernah di klik
        let elementBefore = null;
        function selectedHour(scheduleId, hourId, el) {
            //jika element sebelumnya ada, dan sekarang pindah ke element lain kliknya. ubah element sebelumnya jadi putih lagi
            if (elementBefore) {
                //ubah styling css : style.property
                elementBefore.style.background = "";
                elementBefore.style.color = "";
                //property css kebab (border-color) di js jadi camel (borderColor)
                elementBefore.style.borderColor = "";
            }
            //kasih warna ke element baru
            el.style.background = "#112646";
            el.style.color = "white";
            el.style.borderColor = "#112646";
            //update element sebelumnya pake element baru
            elementBefore = el;

            let wrapBtn = document.querySelector("#wrapBtn");
            let btnTicket = document.querySelector("#btnTicket");
            //kasih warna biru ke div wrap dan hilangkan warna abu
            //warna abu dari 'bg-light' class bootstrap
            wrapBtn.classList.remove('bg-light');
            wrapBtn.style.background = "#112646";
            //siapkan route
            let url = "{{ route('schedules.show_seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}"
                .replace(':scheduleId', scheduleId) //ubah parameter (:) dengan value dari js
                .replace(':hourId', hourId);
            //isi url ke href btnTicket
            btnTicket.href = url;
            btnTicket.style.color = 'white';
        }
    </script>

@endpush
