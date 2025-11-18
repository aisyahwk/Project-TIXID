@extends('templates.app')

@section('content')
    <div class="card w-25 d-block mx-auto my-5 p-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('tickets.export.pdf', $ticket['id']) }}" class="btn btn-secondary">Unduh (.pdf)</a>
            </div>
            {{-- loooping berdasarkan jumlah kursi yang dipilih --}}
            @foreach ($ticket['rows_of_seats'] as $kursi)
                <div class="w-100 mb-4">
                    <p class="text-right"><b>{{ $ticket['schedule']['cinema']['name'] }}</b></p>
                    <hr>
                    <b>{{ $ticket['schedule']['movie']['title'] }}</b>
                    <p>Tanggal : {{ \Carbon\Carbon::parse($ticket['ticketPayment']['booked_date'])->format('d F, Y') }}</p>
                    <p>Waktu : {{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}</p>
                    <p>Kursi : {{ $kursi }}</p>
                    <p>Harga Tiket : Rp. {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
