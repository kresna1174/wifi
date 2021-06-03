@extends('layout.main')
@section('content')
<h1 class="page-header">
        Create Pembayaran
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
    {!! Form::open(['id' => 'form-create']) !!}
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Pelanggan</label>
                    {!! Form::select('nama_pelanggan', ['' => 'select'] + $pelanggan->toArray(), null, ['class' => 'form-control', 'id' => 'nama_pelanggan']) !!}
                </div>
                <div class="form-group">
                    <label>No Pemasangan</label>
                    {!! Form::select('no_pemasangan', ['' => 'select'] + $pemasangan->toArray(), null, ['class' => 'form-control', 'id' => 'no_pemasangan']) !!}
                </div>
            </div>
            <div class="col-6">
                <label>Alamat Pemasangan</label>
                {!! Form::textarea('alamat_pemasangan', null, ['class' => 'form-control', 'id' => 'alamat_pemasangan']) !!}
                {!! Form::hidden('id_tagihan', $pemasangan->p, ['class' => 'form-control', 'id' => 'id_tagihan']) !!}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <table id="table" class="table table-bordered table-consoned" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tarif</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Total Bayar</label>
                    {!! Form::text('total_bayar', null, ['class' => 'form-control text-right', 'id' => 'total_bayar', 'readonly']) !!}
                    {!! Form::hidden('id_bayar', null, ['class' => 'form-control text-right', 'id' => 'id_bayar', 'readonly']) !!}
                </div>
                <div class="form-group">
                    <label>Tunai</label>
                    {!! Form::text('tunai', null, ['class' => 'form-control text-right', 'id' => 'tunai']) !!}
                </div>
                <div class="float-right">
                    <button type="button" class="btn btn-secondary" onclick="document.location.href='<?= route('pembayaran') ?>'">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="store()">Bayar</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
</div>
@endsection

@section('script')
    <script>
        $('#nama_pelanggan').change(function() {
            $.ajax({
                url: '<?= route('pembayaran.get_pembayaran') ?>?id_pelanggan=' + $('#nama_pelanggan').val(),
                success: function(response) {
                    if(!$.trim(response)) {
                        $('#table tbody').html('')
                        $('#alamat_pemasangan').val('')
                        $('#total_bayar').val('')
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
                        $('#total_bayar').val(row.tarif)
                        $('#id_bayar').val(row.id)
                    })
                }
            })
        })

        function store() {
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
                    } else {
                        $.growl.notice({
                            title : 'success',
                            message : 'Data Berhasil di Tambahkan'
                        }); 
                    }
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

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection