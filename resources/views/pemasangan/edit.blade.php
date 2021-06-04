@extends('layout.main')
@section('content')
<h1 class="page-header">
        pemasangan
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::model($pelanggan, ['id' => 'form-edit'])!!}
@include('pemasangan.form')
<div class="float-right">
    <button type="button" class="btn btn-secondary" onclick="document.location.href='<?= route('pemasangan') ?>'">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="update('<?= $pelanggan->id ?>')">Update</button>
</div>
{!! Form::close() !!}
</div>
</div>
@endsection

@section('script')
    <script>
    $(function() {
        $('#pilih_pelanggan').prop('readonly', true)
        $('#no_telepon').prop('readonly', true)
        $('#nama_pelanggan').prop('readonly', true)
        $('#no_identitas').prop('readonly', true)
        $('#alamat').prop('readonly', true)
        $('#pilih_pelanggan').remove()
        $('.pilih_pelanggan').html('<input type="text" name="pilih_pelanggan" class="form-control" id="pilih_pelanggan" value="pelanggan lama" readonly>')
        var url = window.location.pathname;
        var id = url.substring(url.lastIndexOf('/') + 1);
        $.ajax({
            url: '<?= route('pemasangan.get_id_pemasangan') ?>?id_pemasangan=' + id,
            success: function(response) {
                $('#nama_pelanggan').remove()
                $('.nama_pelanggan').html('<input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" value="'+response.nama_pelanggan+'" readonly>')
            }
        })
    })

    function update(id) {
        $.ajax({
            url: '<?= route('pemasangan.update') ?>/' +id,
            dataType: 'json',
            type: 'post',
            data: $('#form-edit').serialize(),
            success: function(response) {
                if(response.success) {
                    $.growl.notice({
                        title : 'success',
                        message : 'Data Berhasil di Update'
                    });
                    return document.location.href='<?= route('pemasangan') ?>'
                } else {
                    $.growl.notice({
                        title : 'success',
                        message : 'Data Gagal di Update'
                    });
                }
            }
        })
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