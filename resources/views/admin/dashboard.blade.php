@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5>Grafik Pembelian Tiket</h5>
        <div class="row">
            <div class="col-6">
                <h5>Data Pembelian Tiket Bulan {{ now()->format('F') }}</h5>
                <canvas id="chartBar"></canvas>
            </div>
            <div class="col-6">
                <h5>Data Film Berdasarkan Status</h5>
                <canvas id="chartPie" class="w-50 h-75 ps-5"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let labelBar = [];
        let dataBar = [];
        let labelPie = [];
        let dataPie = [];
        // ketika html selesai di render, jalankan fungsi js ini
        $(function () {
            $.ajax({
                url: "{{ route('admin.tickets.chart') }}",
                method: "GET",
                success: function (response) {
                    labelBar = response.labels;
                    dataBar = response.data;
                    chartBar()
                },
                error: function (err) {
                    alert('Gagal mengambil data untuk chart bar!');
                }
            });

            $.ajax({
                url: "{{ route('admin.movies.chart') }}",
                method: "GET",
                success: function (response) {
                    labelPie = response.labels;
                    dataPie = response.data;
                    chartPie()
                },
                error: function (err) {
                    alert('Gagal mengambil data untuk chart pie!');
                }
            });
        });
        const ctx = document.getElementById('chartBar');
        function chartBar() {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelBar,
                    datasets: [{
                        label: 'Penjualan Tiket Bulan Ini',
                        data: dataBar,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const ctx2 = document.getElementById('chartPie');
        function chartPie() {
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: labelPie,
                    datasets: [{
                        label: 'Data Film Berdasarkan Status',
                        data: dataPie,
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ],
                        hoverOffset: 4
                    }]
                }
            })
        }
    </script>
@endpush
