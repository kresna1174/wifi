@extends('layout.main')
@section('content')
<h1 class="page-header">
        userService
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
                    <th>Username</th>
                    <th>Dibuat</th>
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
                ajax: '<?= route('UserService.get') ?>',
                columns: [
                    // {data: 'id', name: 'id'},
                    {data: 'username', name: 'username'},
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
                url: '<?= route('UserService.create') ?>',
                success: function(response) {
                    bootbox.dialog({
                        title: 'Create users',
                        message: response
                    })
                }
            })
        }

        
        function store() {
            $('#form-create .alert').remove()
            $.ajax({
                url: '<?= route('UserService.store') ?>',
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
                url: '<?= route('UserService.update') ?>/'+id,
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

        function edit(id) {
            $.ajax({
                url: '<?= route('UserService.edit') ?>/'+id,
                success: function(response) {
                    bootbox.dialog({
                        title: 'Edit Users',
                        message: response
                    })
                }
            })
        }
        

        function destroy(id) {
            $.ajax({
                url: '<?= route('UserService.delete') ?>/'+id,
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