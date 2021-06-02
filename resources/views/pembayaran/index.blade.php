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

        function store() {
            $('#form-create .alert').remove()
            $.ajax({
                url: '<?= route('pelanggan.store') ?>',
                dataType: 'json',
                type: 'post',
                data: $('#form-create').serialize(),
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
                    $('#form-create').prepend(validation(response));
                }
            })
        }
        
        function update(id) {
            $('#form-edit .alert').remove()
            $.ajax({
                url: '<?= route('pelanggan.update') ?>/'+id,
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
                url: '<?= route('pelanggan.delete') ?>/'+id,
                success: function(response) {
                    alert(response);
                }
            })
            dataTable.ajax.reload()
        }

        function validation(errors) {
            var validations = '<div class="alert alert-danger">';
                validations += '<p><b>'+errors.message+'</b></p>';
                $.each(errors.errors, function(i, error){
                    validations += error[0]+'<br>';
                });
                validations += '</div>';
            return validations;
        }

    </script>
@endsection