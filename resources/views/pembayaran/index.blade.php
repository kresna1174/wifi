@extends('layout.main')
@section('content')
<h1 class="page-header">
        Pembayaran
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <button type="button" id="btn-create" class="btn btn-primary" onclick="document.location.href='<?= route('pembayaran.create') ?>'">Create</button>
                </div>
            </div>
        </div>
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="table" class="table table-consoned table-bordered" width="100%">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Bayar</th>
                    <th>Tarif</th>
                    <th>Alamat Pemasangan</th>
                    <th>Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
    <script>
        let dataTable;
        $(function() {
            dataTable = $('#table').DataTable({
                ajax: '<?= route('pembayaran.get') ?>',
                columns: [
                    {data: 'nama_pelanggan', name: 'nama_pelanggan'},
                    {data: 'bayar', name: 'bayar'},
                    {data: 'tarif', name: 'tarif'},
                    {data: 'alamat_pemasangan', name: 'alamat_pemasangan'},
                    {data: 'tanggal_bayar', name: 'tanggal_bayar'},
                ]
            })
        })
        
        function view(id) {
            $.ajax({
                url: '<?= route('pelanggan.view') ?>/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'View Pelanggan',
                        message: response
                    })
                }
            })
        }
    </script>
@endsection