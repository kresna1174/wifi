@extends('layout.main')
@section('content')
<h1 class="page-header">
        Deposit
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
                    <th>Nama Pelanggan</th>
                    <th>No Pemasangan</th>
                    <th class="text-center">Jumlah Deposit</th>
                    <th class="text-center">Tanggal Deposit</th>
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
                ajax: '<?= route('deposit.get') ?>',
                columns: [
                    {data: 'nama_pelanggan', name: 'nama_pelanggan'},
                    {data: 'no_pemasangan', name: 'no_pemasangan'},
                    {data: 'jumlah_deposit', name: 'jumlah_deposit', class: 'text-center',render: function(data) {
                        return pop(data)
                    }},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'id', name: 'deposit.id', width: '200px', searchable: false, orderable: false, class: 'text-center nowrap',mRender: function(data){
                    return '<button id="btn-view" type="button" class="btn btn-info btn-sm" onclick="view('+data+')">View</button> \n\
                            <button id="btn-edit" type="button" class="btn btn-warning btn-sm" onclick="edit('+data+')">Edit</button>\n\
                            <button type="button" class="btn btn-danger btn-sm" onclick="destroy('+data+')">Delete</button>';
                }}
                ]
            })
        })

        function create() {
            $.ajax({
                url: '<?= route('deposit.create') ?>',
                success: function(response) {
                    bootbox.dialog({
                        title: 'Create Pelanggan',
                        message: response,
                    })
                    $('#jumlah_deposit').number(true, 2, ',', '.')
                }
            })
        }

        function edit(id) {
            $.ajax({
                url: '<?= route('deposit.edit') ?>/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Pelanggan',
                        message: response
                    })
                    $('#jumlah_deposit').number(true, 2, ',', '.')
                }
            })
        }
        
        function view(id) {
            $.ajax({
                url: '<?= route('deposit.view') ?>/'+id,
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
                url: '<?= route('deposit.store') ?>',
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
                url: '<?= route('deposit.update') ?>/'+id,
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

        function destroy(id){
            Swal.fire({
            title: 'Delete',
            text: 'Apakah anda yakin akan menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#929ba1',
            confirmButtonText: 'Oke'
            }).then((result) => {
                if (result.value) {         
                    $.ajax({
                        url: '<?= route('deposit.delete') ?>/'+id,
                        success: function(response){
                            if(response.success) {
                                $.growl.notice({
                                    title : 'success',
                                    message : response.message
                                });
                                dataTable.ajax.reload();
                            } else {
                                $.growl.error({
                                    title : 'failed',
                                    message : response.message
                                });
                            }
                        }
                    });
                }
            });
        }

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
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