<div class="form-group">
    <label>No Pelanggan</label>
    {!! Form::text('no_pelanggan', $model->no_pelanggan ?? $pelanggan, ['class' => 'form-control', 'id' => "no_pelanggan", 'readonly']) !!}
</div>
<div class="form-group">
    <label>Nama Pelanggan</label>
    {!! Form::text('nama_pelanggan', null, ['class' => 'form-control', 'id' => "nama_pelanggan"]) !!}
</div>
<div class="form-group">
    <label>No Telepon</label>
    {!! Form::number('no_telepon', null, ['class' => 'form-control', 'id' => "no_telepon"]) !!}
</div>
<div class="form-group">
    <label>Jenis Identitas</label>
    {!! Form::select('identitas', ['' => 'select', '1' => 'KTP', '2' => 'SIM', '3' => 'KARTU PELAJAR'], null, ['class' => 'form-control', 'id' => "identitas"]) !!}
</div>
<div class="form-group">
    <label>No Indentitas</label>
    {!! Form::number('no_identitas', null, ['class' => 'form-control', 'id' => "no_identitas"]) !!}
</div>
<div class="form-group">
    <label>Alamat</label>
    {!! Form::textarea('alamat', null, ['class' => 'form-control', 'id' => "alamat"]) !!}
</div>