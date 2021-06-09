@extends('layout.main')
@section('content')
<h1 class="page-header">
        UserService
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::open(['id' => 'form-create'])!!}
@include('userServices.form')
<div class="float-right">
    <button type="button" class="btn btn-secondary" onclick="document.location.href='<?= route('UserService') ?>'">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="store()">Store</button>
</div>
{!! Form::close() !!}
</div>
</div>
@endsection

@section('script')
     <script>
           function store() {
            if($('#name').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Username Harus di Isi'
                });
            }
            if($('#password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Password Harus di Isi'
                });
            }
            if($('#konfirmasi_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Password Harus di Isi'
                });
            }
            if($('#password').val() != $('#konfirmasi_password').val()) {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Harus Sama'
                });

            }
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
                            message : response.message
                        });
                    } else {
                        $.growl.error({
                            title : 'failed',
                            message : response.message
                        });
                    }
                },
                error: function(xhr) {
                    let response = JSON.parse(xhr.responseText);
                    $('#form-create').prepend(validation(response));
                }
            })
        } 

        
     </script>
@endsection