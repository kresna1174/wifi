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
    let data = '<?= json_encode($pelanggan) ?>'
        $('#pilih_pelanggan').change(function() {
            if($('#pilih_pelanggan').val() == '0') {
                let html = '<div class="form-group">';
                html += '<label>Nama Pelanggan</label>';
                html += '<select class="form-control" name="nama_pelanggan" id="nama_pelanggan">'
                    $.each(JSON.parse(data), function(data, row) {
                        html += '<option value="'+row.id+'">'+row.nama_pelanggan+'</option>'
                    })
                html += '</select>'
                html += '</div>'
                $('.nama').html(html)
            } 
            if($('#pilih_pelanggan').val() == '1') {
                let html = '<div class="form-group">';
                html += '<label>Nama Pelanggan</label>';
                html += '<input name="nama_pelanggan" id="nama_pelanggan" class="form-control">'
                html += '</div>'
                $('.nama').html(html)
            }
            if($('#pilih_pelanggan').val() == '') {
                $('.nama').html('')
                $('#no_telepon').val('')
                $('#no_ktp').val('')
                $('#alamat').val('')
            }
        if($('#nama_pelanggan').val() != null) {
            $.ajax({
                url: '<?= route('pemasangan.get_pemasangan') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
                success: function(response) {
                    if (!$.trim(response)){
                        $('#no_telepon').val('')
                        $('#no_ktp').val('')
                        $('#alamat').val('')
                    } else {
                        $('#no_telepon').val(response.no_telepon)
                        $('#no_ktp').val(response.no_ktp)
                        $('#alamat').val(response.alamat)
                    }
                }
            })
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
    

    </script>
@endsection