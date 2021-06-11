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
    $('#tanggal_pemasangan').datepicker({
        format: 'yyyy-mm-dd'
    })
    $('#tarif').number(true, 2, ',', '.');
    let no_pelanggan = '<?= json_encode($pelanggan->no_pelanggan) ?>'
    let pelanggan = '<?= json_encode($pelanggan->data) ?>'
    function pilih() {
        if($('#pilih_pelanggan').val() == 1) {
            $.ajax({
                url: '<?= route('pemasangan.get_pemasangan') ?>',
                success: function(response) {
                    let html = '<select class="form-control" name="nama_pelanggan" id="nama_pelanggan" onchange="change_pelanggan()">'
                    html += '<option value="">select</option>'
                    $.each(response, function(data, row) {
                        html += '<option value="'+row.id+'">'+row.nama_pelanggan+'</option>'
                    })
                    html +='</select>'
                    $('#nama_pelanggan').remove()
                    $('.nama_pelanggan').html(html)
                    $('#nama_pelanggan').select2()
                }
            })
        } 

        if($('#pilih_pelanggan').val() == 2) {
            let html = '<input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan">'
            $('#nama_pelanggan').remove()
            $('.nama_pelanggan').html(html)
            $('#no_telepon').val('').prop('readonly', false)
            $('#no_identitas').val('').prop('readonly', false)
            $('#alamat').val('').prop('readonly', false)
            $('#identitas').val('').prop('disabled', false)
            $('#no_pelanggan').val(JSON.parse(no_pelanggan)).prop('readonly', true)
        }

        if($('#pilih_pelanggan').val() == '') {
            $('#no_telepon').val('').prop('readonly', false)
            $('#no_identitas').val('').prop('readonly', false)
            $('#alamat').val('').prop('readonly', false)
            $('#identitas').val('').prop('disabled', false)
            $('#no_pelanggan').val(JSON.parse(no_pelanggan)).prop('readonly', true)
        }
    }

    function change_pelanggan() {
        $.ajax({
            url: '<?= route('pemasangan.get_pemasangan') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
            success: function(response) {
                $('#no_telepon').val(response.no_telepon).prop('readonly', true)
                $('#no_identitas').val(response.no_identitas).prop('readonly', true)
                $('#alamat').val(response.alamat).prop('readonly', true)
                $('#identitas').val(response.identitas).prop('disabled', true)
                $('#no_pelanggan').val(response.no_pelanggan).prop('readonly', true)
            }
        })
    }

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
                        message : response.message
                    });
                    return document.location.href='<?= route('pemasangan') ?>'
                } else {
                    $.growl.error({
                        title : 'failed',
                        message : response.message
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