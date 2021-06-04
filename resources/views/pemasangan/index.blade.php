@extends('layout.main')
@section('content')
<h1 class="page-header">
        pemasangan
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <button type="button" id="btn-create" class="btn btn-primary" onclick="document.location.href='<?= route('pemasangan.create') ?>'">Create</button>
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
                    <th>Alamat Pelanggan</th>
                    <th>Tarif</th>
                    <th>Tanggal</th>
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
                ajax: '<?= route('pemasangan.get') ?>',
                columns: [
                    {data: 'nama_pelanggan', name: 'nama_pelanggan'},
                    {data: 'alamat_pemasangan', name: 'pemasangan.alamat_pemasangan'},
                    {data: 'tarif', name: 'pemasangan.tarif'},
                    {data: 'tanggal_pemasangan', name: 'pemasangan.tanggal_pemasangan'},
                    {data: 'id', name: 'id', width: '200px', searchable: false, orderable: false, class: 'text-center nowrap',mRender: function(data){
                    return '<a role="button" href="<?= route('pemasangan.edit') ?>/'+data+'" id="btn-edit" type="button" class="btn btn-warning btn-sm">edit</a>\n\
                            <button type="button" class="btn btn-danger btn-sm" onclick="destroy('+data+')">delete</button>';
                }}
                ]
            })
        })

        

        
        

        function update(id) {
            $('#form-edit .alert').remove()
            $.ajax({
                url: '<?= route('pemasangan.update') ?>/'+id,
                dataType: 'json',
                type: 'post',
                data: $('#form-edit').serialize(),
                success: function(response) {
                    if(response.success) {
                        $.growl.notice({
                            title : 'success',
                            message : 'Data Berhasil di Update'
                        });
                    } else {
                        $.growl.notice({
                            title : 'false',
                            message : 'Data Gagal di Update'
                        });
                    }
                    bootbox.hideAll()
                    dataTable.ajax.reload()
                },
                error: function(xhr) {
                    let response = JSON.parse(xhr.responseText);
                    $('#form-edit').prepend(validation(response));
                }
            })
        }


        

        function destroy(id) {
            $.ajax({
                url: '<?= route('pemasangan.delete') ?>/'+id,
                success: function(response) {
                    alert(response);
                }
            })
            dataTable.ajax.reload()
        }

        


    </script>
@endsection