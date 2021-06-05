@extends('layout.main')
@section('content')
<h1 class="page-header">
        Create Pembayaran
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
    {!! Form::open(['id' => 'form-create']) !!}
        @include('pembayaran.form')
        <div class="float-right">
            <button type="button" class="btn btn-secondary" onclick="document.location.href='<?= route('pembayaran') ?>'">Cancel</button>
            <button type="button" class="btn btn-success" id="store_bayar" onclick="store()">Bayar</button>
        </div>
    {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
    <script>
    $(function() {
        if($('#bayar').val() == '') {
            $('#store_bayar').prop('disabled', true)
        } else {
            $('#store_bayar').prop('disabled', false)
        }
    })

    function unblock() {
        if($('#bayar').val() == '') {
            $('#store_bayar').prop('disabled', true)
        } else {
            $('#store_bayar').prop('disabled', false)
        }
    }

    if($('#nama_pelanggan').val() == '' && $('#no_pemasangan').val() == '') {
        $('#nama_pelanggan').change(function() {
            $('#no_pemasangan').change(function() {
                $.ajax({
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&id_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            $.each(response, function(data, row) {
                                let html = '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+pop(row.tarif)+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        $.each(response, function(data, row) {
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(pop(row.tarif))
                            $('#id_tagihan').val(row.tagihan_id)
                            if($('#bayar').val() == '') {
                                $('#store_bayar').prop('disabled', true)
                            } else {
                                $('#store_bayar').prop('disabled', false)
                            }
                        })
                    }
                })
            })
        })
    }

    if($('#nama_pelanggan').val() == '') {
        $('#nama_pelanggan').change(function() {
            $.ajax({
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&id_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            $.each(response, function(data, row) {
                                let html = '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+pop(row.tarif)+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        $.each(response, function(data, row) {
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(pop(row.tarif))
                            $('#id_tagihan').val(row.tagihan_id)
                            if($('#bayar').val() == '') {
                                $('#store_bayar').prop('disabled', true)
                            } else {
                                $('#store_bayar').prop('disabled', false)
                            }
                        })
                    }
                })
        })
    }
    if($('#no_pemasangan').val() == '') {
        $('#no_pemasangan').change(function() {
            $.ajax({
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&id_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            $.each(response, function(data, row) {
                                let html = '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+pop(row.tarif)+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        $.each(response, function(data, row) {
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(pop(row.tarif))
                            $('#id_tagihan').val(row.tagihan_id)
                            if($('#bayar').val() == '') {
                                $('#store_bayar').prop('disabled', true)
                            } else {
                                $('#store_bayar').prop('disabled', false)
                            }
                        })
                    }
                })
        })
    }

        function store() {
            if($('#bayar').val() > $('#total_bayar').val().replace('.', '')) {
                let total_bayar = $('#bayar').val() - $('#total_bayar').val().replace('.', '');
                let bayar = $('#bayar').val() - total_bayar
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Apakah Anda Ingin Deposit ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#929ba1',
                    confirmButtonText: 'Oke'
                }).then((result) => {
                if (result.value) { 
                    $('#deposit').val(total_bayar)
                    $('#bayar').val(bayar)
                    $.ajax({
                        url: '<?= route('pembayaran.store') ?>',
                        dataType: 'json',
                        type: 'post',
                        data: $('#form-create').serialize(),
                        success: function(response) {
                            if(response.success) {
                                $.growl.notice({
                                    title : 'success',
                                    message : response.message
                                });
                                return document.location.href='<?= route('pembayaran') ?>'
                            } else {
                                $.growl.error({
                                    title : 'failed',
                                    message : response.message
                                });
                            }
                        }
                    })
                }
            });
            } else {
                $.ajax({
                    url: '<?= route('pembayaran.store') ?>',
                    dataType: 'json',
                    type: 'post',
                    data: $('#form-create').serialize(),
                    success: function(response) {
                        if(response.success) {
                            $.growl.notice({
                                title : 'success',
                                message : 'Data Berhasil di Tambahkan'
                            });
                            return document.location.href='<?= route('pembayaran') ?>'
                        } else {
                            $.growl.notice({
                                title : 'failed',
                                message : 'Data Gagal di Tambahkan'
                            }); 
                        }
                    }
                })
            }
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

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection