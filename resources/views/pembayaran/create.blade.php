@extends('layout.main')
@section('content')
<h1 class="page-header">
    Create Pembayaran
</h1>
<?php if(session('error')) { ?>
    <div class="alert alert-danger error">
        {{session('error')}}
    </div>
<?php } ?>    
<?php if(session('success')) { ?>
    <div class="alert alert-danger success">
        {{session('success')}}
    </div>
<?php } ?>    
<div class="panel panel-default">
    <div class="panel-body">
    {!! Form::model(['id' => 'form-create']) !!}
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
        $('#bayar').number(true, 2, ',', '.');
        $('#total_bayar').number(true, 2, ',', '.');
        if($('#bayar').val() == '') {
            $('#store_bayar').prop('disabled', true)
        } else {
            $('#store_bayar').prop('disabled', false)
        }

        if($('#nama_pelanggan').val() != null && $('#no_pemasangan').val() != null) {
            $.ajax({
                url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&no_pemasangan=' + $('#no_pemasangan').val(),
                success: function(response) {
                    let html;
                    let tagihan;
                    $.each(response, function(data, row) {
                        html += '<tr>'
                        html += '<td>'+row.tanggal_tagihan+'</td>'
                        html += '<td class="text-right">'+row.tagihan+'</td>'
                        html += '</tr>'
                        $('#table tbody').html(html)
                        if(data > 0) {
                            tagihan += row.tagihan       
                        } else {
                            tagihan = row.tagihan
                        }
                        $('#alamat_pemasangan').val(row.alamat_pemasangan)
                        $('#total_bayar').val(tagihan)
                        $('#id_tagihan').val(row.tagihan_id)
                        if($('#bayar').val() == '') {
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            $('#store_bayar').prop('disabled', false)
                        }
                    })
                }
            })
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
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&no_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            let html;
                            $.each(response, function(data, row) {
                                html += '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+row.tagihan+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        let tagihan;
                        $.each(response, function(data, row) {
                            if(data > 0) {
                                tagihan += row.tagihan       
                            } else {
                                tagihan = row.tagihan
                            }
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(tagihan)
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
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&no_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            let html;
                            $.each(response, function(data, row) {
                                html += '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+row.tagihan+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        let tagihan;
                        $.each(response, function(data, row) {
                            if(data > 0) {
                                tagihan += row.tagihan       
                            } else {
                                tagihan = row.tagihan
                            }
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(tagihan)
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
                    url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val() + '&no_pemasangan=' + $('#no_pemasangan').val(),
                    success: function(response) {
                        if(!$.trim(response)) {
                            $('#table tbody').html('')
                            $('#alamat_pemasangan').val('')
                            $('#total_bayar').val('')
                            $('#store_bayar').prop('disabled', true)
                        } else {
                            let html;
                            $.each(response, function(data, row) {
                                html += '<tr>'
                                html += '<td>'+row.tanggal_tagihan+'</td>'
                                html += '<td class="text-right">'+row.tagihan+'</td>'
                                html += '</tr>'
                                $('#table tbody').html(html)
                            })
                        }
                        let tagihan;
                        $.each(response, function(data, row) {
                            if(data > 0) {
                                tagihan += row.tagihan       
                            } else {
                                tagihan = row.tagihan
                            }
                            $('#alamat_pemasangan').val(row.alamat_pemasangan)
                            $('#total_bayar').val(tagihan)
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
            if($('#bayar').val() - $('#total_bayar').val() != 0 && $('#bayar').val() - $('#total_bayar').val() > 0) {
                let total_bayar = $('#bayar').val() - $('#total_bayar').val();
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
            } if($('#total_bayar').val() - $('#bayar').val() != 0 && $('#total_bayar').val() - $('#bayar').val() > 0) {
                let total_bayar = $('#total_bayar').val() - $('#bayar').val();
                $('#sisa').val(total_bayar)
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
            } if($('#bayar').val() - $('#total_bayar').val() == 0 && $('#total_bayar').val() - $('#bayar').val() == 0) {
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
            // return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection