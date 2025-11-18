@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('staff.promos.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('staff.promos.create')}}" class="btn btn-success">Tambah Data</a>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <h5>Data Promo</h5>
        <table class="table table-bordered" id="promoTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Promo</th>
                    <th>Total Potongan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#promoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.promos.datatables') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'promo_code', name: 'promo_code', orderable: true, searchable: true },
                    { data: 'discount_display', name: 'discount_display', orderable: true, searchable: true },
                    { data: 'buttons', name: 'buttons', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush
