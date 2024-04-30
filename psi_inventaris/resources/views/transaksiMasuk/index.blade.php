@extends('layouts.template')
@section('content')
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('transaksiMasuk/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_transaksiMasuk">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Nama Barang</th>
                        {{-- <th>Merk</th> --}}
                        {{-- <th>Spesifikasi</th> --}}
                        <th>Volume</th>
                        <th>Satuan</th>
                        {{-- <th>Harga Satuan</th> --}}
                        <th>Tanggal Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script>
        $(document).ready(function() {
            var dataTransaksiMasuk = $('#table_transaksiMasuk').DataTable({
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('transaksiMasuk/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [{
                        data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn() 
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "barang.barang_nama",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    // {
                    //     data: "barang.merk",
                    //     className: "",
                    //     orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                    //     searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    // }, 
                    // {
                    //     data: "barang.spesifikasi",
                    //     className: "",
                    //     orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                    //     searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    // }, 
                    {
                        data: "qty",
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    }, {
                        data: "barang.satuan",
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }, {
                        data: "tanggal_diterima",
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }, {
                        data: "aksi",
                        className: "text-center",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari 
                    }
                ]
            });
        });
    </script>
@endpush