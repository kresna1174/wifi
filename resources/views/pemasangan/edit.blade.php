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
    <button type="button" class="btn btn-primary" onclick="update('<?= $pelanggan->id_pemasangan ?>')">Update</button>
</div>
{!! Form::close() !!}
</div>
</div>
@endsection

@section('script')
    <script>
    $('#tanggal_pemasangan').datepicker({
        format: 'yyyy-mm-dd'
    })
    $('#tanggal_tagihan').datepicker({
        format: 'yyyy-mm-dd'
    })
    $('#tarif').number(true, 2, ',', '.');

    
    $(function() {
        $('#pilih_pelanggan').prop('readonly', true)
        $('#no_telepon').prop('readonly', true)
        $('#nama_pelanggan').prop('readonly', true)
        $('#no_identitas').prop('readonly', true)
        $('#alamat').prop('readonly', true)
        $('#no_pelanggan').prop('readonly', true)
        $('#identitas').prop('disabled', true)
        $('#pilih_pelanggan').remove()
        $('.pilih_pelanggan').html('<input type="text" name="pilih_pelanggan" class="form-control" id="pilih_pelanggan" value="pelanggan lama" readonly>')
    })

    function change_pelanggan() {
        $.ajax({
            url: '<?= route('pemasangan.get_pemasangan') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
            success: function(response) {
                $('#no_telepon').val(response.no_telepon).prop('readonly', true)
                $('#no_identitas').val(response.no_identitas).prop('readonly', true)
                $('#alamat').val(response.alamat).prop('readonly', true)
                $('#identitas').val(response.identitas).prop('readonly', true)
                $('#no_pelanggan').val(response.no_pelanggan).prop('readonly', true)
            }
        })
    }

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
                        message : response.message
                    });
                    return document.location.href='<?= route('pemasangan') ?>'
                } else {
                    $.growl.error({
                        title : 'failed',
                        message : response.message
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