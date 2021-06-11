@extends('layout.main')
@section('content')
<h1 class="page-header">
    Create User Service
</h1>
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::open(['id' => 'form-create'])!!}
<div class="form-group">
    <label>Username</label>
    {!! Form::text('name', null, ['class' => 'form-control', 'id' => "name"]) !!}
</div>
<div class="form-group">
    <label>Password</label>
    {!! Form::password('password', ['class' => 'form-control', 'id' => "password"]) !!}
</div>
<div class="form-group">
    <label>Konfirmasi Password</label>
    {!! Form::password('konfirmasi_password', ['class' => 'form-control', 'id' => "konfirmasi_password"]) !!}
</div>
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
                return;
            }
            if($('#password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Password Harus di Isi'
                });
                return;
            }
            if($('#konfirmasi_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Password Harus di Isi'
                });
                return;
            }
            if($('#password').val() != $('#konfirmasi_password').val()) {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Harus Sama'
                });
                return;

            }
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
                        return document.location.href='<?= route('UserService') ?>'
                    } else {
                        $.growl.error({
                            title : 'failed',
                            message : response.message
                        });
                    }
                }
            })
        } 

        
     </script>
@endsection