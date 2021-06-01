@extends('layout.main')
@section('content')
<h1 class="page-header">
        Pelanggan
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <button type="button" id="btn-create" class="btn btn-primary" onclick="create()">Create</button>
                </div>
            </div>
        </div>
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="table" class="table table-consoned table-bordered" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>No Telepon</th>
                    <th>No Identitas</th>
                    <th>Alamat</th>
                    <th></th>
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
                ajax: '<?= route('pelanggan.get') ?>',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nama_pelanggan', name: 'nama_pelanggan'},
                    {data: 'no_telepon', name: 'no_telepon'},
                    {data: 'no_ktp', name: 'no_ktp'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'id', name: 'id', width: '200px', searchable: false, orderable: false, class: 'text-center nowrap',mRender: function(data){
                    return '<button id="btn-view" type="button" class="btn btn-info btn-sm" onclick="view('+data+')">view</button> \n\
                            <button id="btn-edit" type="button" class="btn btn-warning btn-sm" onclick="edit('+data+')">edit</button>\n\
                            <button type="button" class="btn btn-danger btn-sm" onclick="destroy('+data+')">delete</button>';
                }}
                ]
            })
        })

        function create() {
            $.ajax({
                url: '<?= route('pelanggan.create') ?>',
                success: function(response) {
                    bootbox.dialog({
                        title: 'Create Pelanggan',
                        message: response
                    })
                }
            })
        }

        function edit(id) {
            $.ajax({
                url: '<?= route('pelanggan.edit') ?>/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Pelanggan',
                        message: response
                    })
                }
            })
        }
        
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

        function destroy(id) {
            $.ajax({
                url: '<?= route('pelanggan.delete') ?>/'+id,
                success: function(response) {
                    alert(response);
                }
            })
            dataTable.ajax.reload()
        }

    </script>
@endsection