@extends('layout.main')
@section('content')
<h1 class="page-header">
        User Service
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <button type="button" id="btn-create" class="btn btn-primary" onclick="document.location.href='<?= route('UserService.create') ?>'">Create</button>
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
                    {data: 'name', name: 'name'},
                    {data: 'id', name: 'id', width: '200px', searchable: false, orderable: false, class: 'text-center nowrap',mRender: function(data){
                    return '<a role="button" href="<?= route('UserService.edit') ?>/'+data+'" id="btn-edit" type="button" class="btn btn-warning btn-sm">Edit</a>\n\
                            <button type="button" class="btn btn-danger btn-sm" onclick="destroy('+data+')">Delete</button>';
                     }
                   }
                ]
            })
        })

        
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
                        url: '<?= route('UserService.delete') ?>/'+id,
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