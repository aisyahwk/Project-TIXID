<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pembelian Tiket</title>
    <style>
        .wrapper {
            width: 400px;
            display: block;
            margin: 20px auto;
            border: 1px solid #eaeaea;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @foreach ($ticket['rows_of_seats'] as $kursi)
            <div style="margin-bottom: 10px">
                <b>{{ $ticket['schedule']['cinema']['name'] }}</b>
                <hr>
                <b>{{ $ticket['schedule']['movie']['title'] }}</b>
                {{-- nama relasi yang camel diganti jadi snack, karena to Arrat di controllernya --}}
                <p>Tanggal : {{ \Carbon\Carbon::parse($ticket['ticket_payment']['booked_date'])->format('d F, Y') }}</p>
                <p>Waktu : {{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}</p>
                <p>Kursi : {{ $kursi }}</p>
                <p>Harga Tiket : Rp. {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
