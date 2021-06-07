@extends('layout.main')
@section('content')
<style>

    .sidebar-bg {
        display: none;
    }

    .sidebar {
        display: none;
    }

    .header {
        display: none;
    }

    body {
        background: #fff;
        margin: 0;
        padding: 0;
    }
</style>


    <div class="container">
        {!! Form::model($model, ['id' => 'form-name']) !!}
            <div class="form-group">
                <label>Foto</label>
                <div class="row">
                    <div class="col-6">
                        <img src="<?= asset('storage') ?>/file/<?= isset($model->foto) ? $model->foto : null ?>" name="pref_foto" alt="" class="img-fluid" id="pref_foto">
                    </div>
                    <div class="col-6">
                        <input type="file" name="foto" id="foto" hidden>
                        <button type="button" class="btn btn-secondary" onclick="pilih_file()">Pilih File ...</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Username</label>
                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" onclick="username()">Save Profile</button>
            </div>
        {!! Form::close() !!}
        <hr>
        {!! Form::open(['id' => 'form-password']) !!}
            <div class="form-group">
                <label>Old Password</label>
                {!! Form::password('old_password', ['class' => 'form-control', 'id' => 'old_password']) !!}
            </div>
            <div class="form-group">
                <label>New Password</label>
                {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password']) !!}
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                {!! Form::password('konfirmasi_password', ['class' => 'form-control', 'id' => 'konfirmasi_password']) !!}
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" onclick="password()">Save Profile</button>
            </div>
        {!! Form::close() !!}
        
    </div>

@endsection

@section('script')
    <script>
        function username() {
            if($('#name').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Username Harus di Isi'
                });
                return false;
            }
            let form = $('#form-name')[0];
            let formData = new FormData(form);
            $.ajax({
                url: '<?= route('setting.change-username') ?>',
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
                    } else {
                        $.growl.error({
                            title : 'failed',
                            message : response.message
                        });
                    }
                }
            })
        }

        function password() {
            if($('#old_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Old Password Harus di Isi'
                });
            }
            if($('#new_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'New Password Harus di Isi'
                });
            }
            if($('#konfirmasi_password').val() == '') {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Password Harus di Isi'
                });
            }
            if($('#new_password').val() != $('#konfirmasi_password').val()) {
                $.growl.error({
                    title : 'Terjadi Kesalahan Input',
                    message : 'Konfirmasi Harus Sama'
                });
                return false;
            }
            $.ajax({
                url: '<?= route('setting.change-password') ?>',
                dataType: 'json',
                type: 'post',
                data: $('#form-password').serialize(),
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
                }
            })
        }

        function pilih_file() {
            $('#foto').click()
        }

        $('#foto').change(function() {
            var output = document.getElementById('pref_foto');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
            URL.revokeObjectURL(output.src)
            }
        })
    </script>
@endsection