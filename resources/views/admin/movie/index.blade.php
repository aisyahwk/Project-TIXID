@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.movies.create')}}" class="btn btn-success">Tambah Data</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success')}}</div>
        @endif
        <h5 class="mt-3">Data Film</h5>
        <table class="table table-bordered" id="movieTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Poster</th>
                    <th>Judul Film</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

        </table>
        <!-- Modal -->
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- mengisi stack--}}
@push('script')
    <script>
        $(function () {
            // $ memanggil JQuery js
            // membuat tampilan datatable di id="movieTable"
            $('#movieTable').DataTable({
                // memberi tanda load pas lagi memproses controller
                processing: true,
                // data yang disajikan di proses di controller (server side)
                serverSide: true,
                // route untuk menuju controller yang memproses data tablea
                ajax: "{{ route('admin.movies.datatables') }}",
                // menentukan urutan td
                columns: [
                    // {data: namaDataAtauNamaColumn, name: namaDataAtauNamaColumn, orderable: TRUE/FALSE, searchable: TRUE/FALSE}
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'imgPoster', name: 'imgPoster', orderable: false, searchable: false },
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'activedBagde', name: 'activedBadge', orderable: true, searchable: true },
                    { data: 'buttons', name: 'buttons', orderable: false, searchable: false }
                ]
            })
        })
        function showModal(item) {
            // console.log(item);
            //mengambil img dgn fungsi php
            //mengaksses folder public dgn fungsi php asset, digabungkan dengan data yg diterima js (item)
            let image = "{{ asset('storage/') }}" + "/" + item.poster;
            // backtip (``) : menyimpan string yang berbaris baris, ada enternya
            let content = `
            <img src="${image}" width="120" class="d-block mx-auto my-2">
            <ul>
            <li>Judul Film : ${item.title}</li>
            <li>Durasi Film : ${item.duration}</li>
            <li>Genre Film : ${item.genre}</li>
            <li>Sutradara : ${item.director}</li>
            <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</span></li>
            <li>Sinopsis : ${item.description}</li>
            </ul>
            `;
            //panggil element HTML yg kan diisi konten diatas : document.querySelector()
            let modalDetailBody = document.querySelector("#modalDetailBody");
            //isi konten HTML : innerHTML
            modalDetailBody.innerHTML = content;
            //panggil element HTML bagian modal
            let modalDetail = document.querySelector("#modalDetail");
            //munculkan modal bootstrap
            new bootstrap.Modal(modalDetail).show();
        }
    </script>
@endpush
