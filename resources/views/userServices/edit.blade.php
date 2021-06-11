@extends('layout.main')
@section('content')
<h1 class="page-header">
    Edit User Service
</h1>
<div class="panel panel-default">
    <div class="panel-body">
        {!! Form::model($model, ['id' => 'form-name']) !!}
            <div class="form-group">
                <label>Username</label>
                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
            </div>
            <div class="form-group pull-right">
                <button type="button" class="btn btn-success" onclick="username('<?= $model->id ?>')">Save Profile</button>
            </div>
        {!! Form::close() !!}
        <hr>
        {!! Form::open(['id' => 'form-password']) !!}
            <div class="form-group">
                <label>New Password</label>
                {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password']) !!}
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                {!! Form::password('konfirmasi_password', ['class' => 'form-control', 'id' => 'konfirmasi_password']) !!}
            </div>
            <div class="form-group pull-right">
                <button type="button" class="btn btn-success" onclick="password('<?= $model->id ?>')">Save Profile</button>
            </div>
        {!! Form::close() !!}
        
    </div>
</div>

@endsection

@section('script')
    <script>
        function username(id) {
            if($('#name').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Username Harus di Isi'
                });
                return;
            }
            let form = $('#form-name')[0];
            let formData = new FormData(form);
            $.ajax({
                url: '<?= route('setting.change-username') ?>?id_pelanggan=' + id,
                dataType: 'json',
                type: 'post',
                enctype: 'multipart/form-data',
                processData: false,
                contentType:false,
                data: formData,
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

        function password(id) {
            if($('#new_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'New Password Harus di Isi'
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
            if($('#new_password').val() != $('#konfirmasi_password').val()) {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Harus Sama'
                });
                return;
            }
            $.ajax({
                url: '<?= route('setting.change-password') ?>?id_pelanggan=' + id,
                dataType: 'json',
                type: 'post',
                data: $('#form-password').serialize(),
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
