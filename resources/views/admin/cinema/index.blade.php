@extends('templates.app')

@section('content')
    <div class='container my-5'>
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.cinemas.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.cinemas.create')}}" class="btn btn-success">Tambah Data</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success mt-3">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('failed'))
            <div class="alert alert-danger mt-3">{{Session::get('failed')}}</div>
        @endif

        <table class="table table-bordered" id="cinemaTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bioskop</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#cinemaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.cinemas.datatables') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'location', name: 'location', orderable: true, searchable: true },
                    { data: 'buttons', name: 'buttons', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
