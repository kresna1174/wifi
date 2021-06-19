<div class="form-group">
    <label>Nama Pelanggan</label>
    {!! Form::select('nama_pelanggan', ['' => '- pilih pelanggan -'] + $pelanggan->toArray(), isset($model->id_pelanggan) ? $model->id_pelanggan : null, ['class' => 'form-control', 'id' => "nama_pelanggan"]) !!}
</div>
<div class="form-group">
    <label>Jumlah Deposit</label>
    {!! Form::text('jumlah_deposit', null, ['class' => 'form-control text-right', 'id' => "jumlah_deposit"]) !!}
</div>