@extends('templates.app')

@section('content')
    <div class='container mt-5'>
        @if (Session::get('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.users.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.users.create')}}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Pengguna (Admin & Staff)</h5>
        <table class="table table-bordered" id="userTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.datatables') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'role', name: 'role', orderable: true, searchable: true },
                    { data: 'buttons', name: 'buttons', orderable: false, searchable: false },
                ]
            })
        })
    </script>
@endpush
