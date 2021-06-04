@extends('layout.main')
@section('content')
<h1 class="page-header">
        pemasangan
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
{!! Form::open(['id' => 'form-create'])!!}
@include('pemasangan.form')
<div class="float-right">
    <button type="button" class="btn btn-secondary" onclick="document.location.href='<?= route('pemasangan') ?>'">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="store()">Store</button>
</div>
{!! Form::close() !!}
</div>
</div>
@endsection

@section('script')
    <script>
    let pelanggan = '<?= json_encode($pelanggan->data) ?>'
    $('#pilih_pelanggan').change(function() {
        if($('#pilih_pelanggan').val() == 0) {
            $.ajax({
                url: '<?= route('pemasangan.get_pemasangan') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
                success: function(response) {
                    if(!$.trim(response)) {
                        $('#nama_pelanggan').remove()
                        $('#no_telepon').val('')
                        $('#no_identitas').val('')
                        $('#alamat').val('')
                        $('#tarif').val('')
                        $('#tanggal_pemasangan').val('')
                        $('#alamat_pemasangan').val('')
                        let html = '<select name="nama_pelanggan" id="nama_pelanggan" class="form-control">'
                        html += '<option value="">select</option>'
                        $.each(JSON.parse(pelanggan), function(key, row) {
                            html += '<option value="'+row.id+'">'+row.nama_pelanggan+'</option>'
                        })
                        html += '</select>'
                        $('.nama_pelanggan').html(html)
                        $('#nama_pelanggan').change(function() {
                            $.ajax({
                                url: '<?= route('pemasangan.get_pemasangan') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
                                success: function(response) {
                                    if(!$.trim(response)) {
                                        $('#no_telepon').val('')
                                        $('#no_identitas').val('')
                                        $('#alamat').val('')
                                        $('#tarif').val('')
                                        $('#tanggal_pemasangan').val('')
                                        $('#alamat_pemasangan').val('')
                                    } else {
                                        $.each(response, function(data, row) {
                                            $('#no_telepon').val(row.no_telepon)
                                            $('#no_identitas').val(row.no_identitas)
                                            $('#alamat').val(row.alamat)
                                            $('#tarif').val('')
                                            $('#tanggal_pemasangan').val('')
                                            $('#alamat_pemasangan').val('')
                                        })
                                    }
                                }
                            })
                        })
                    } else {
                        let html = '<select name="nama_pelanggan" id="nama_pelanggan" class="form-control">'
                        $.each(response, function(data, row) {
                            $('#no_telepon').val(row.no_telepon)
                            $('#no_identitas').val(row.no_identitas)
                            $('#alamat').val(row.alamat)
                            $('#tarif').val('')
                            $('#tanggal_pemasangan').val('')
                            $('#alamat_pemasangan').val('')
                            html += '<option value="'+row.id_pelanggan+'">'+row.nama_pelanggan+'</option>'
                        })
                        html += '</select>'
                        $('.nama_pelanggan').html(html)
                    }
                }
            })
        }

        if($('#pilih_pelanggan').val() == 1) {
            $('#nama_pelanggan').remove()
            $('#no_telepon').val('')
            $('#no_identitas').val('')
            $('#alamat').val('')
            $('#tarif').val('')
            $('#tanggal_pemasangan').val('')
            $('#alamat_pemasangan').val('')
            let html = '<input type="text" name="nama_pelanggan" class="form-control">'
            $('.nama_pelanggan').html(html)
        }
    })

    function store() {
        $('#form-create .alert').remove()
        $.ajax({
            url: '<?= route('pemasangan.store') ?>',
            dataType: 'json',
            type: 'post',
            data: $('#form-create').serialize(),
            success: function(response) {
                if(response.success) {
                    $.growl.notice({
                        title : 'success',
                        message : 'Data Berhasil di Update'
                    });
                    return document.location.href='<?= route('pemasangan') ?>'
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