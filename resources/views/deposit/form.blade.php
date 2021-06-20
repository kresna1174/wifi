<div class="form-group">
    <label>Nama Pelanggan</label>
    {!! Form::select('nama_pelanggan', ['' => '- pilih pelanggan -'] + $pelanggan->toArray(), isset($model->id_pelanggan) ? $model->id_pelanggan : null, ['class' => 'form-control', 'id' => "nama_pelanggan"]) !!}
</div>
<div class="form-group">
    <label>No Pemasangan</label>
    {!! Form::select('no_pemasangan', ['' => '- pilih no pemasangan -'] + $pelanggan->no_pemasangan->toArray(), isset($model->no_pemasangan) ? $model->no_pemasangan : null, ['class' => 'form-control', 'id' => "no_pemasangan"]) !!}
</div>
<div class="form-group">
    <label>Jumlah Deposit</label>
    {!! Form::text('jumlah_deposit', null, ['class' => 'form-control text-right', 'id' => "jumlah_deposit"]) !!}
</div>