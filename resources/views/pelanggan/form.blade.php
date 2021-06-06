<div class="form-group">
    <label>Nama Pelanggan</label>
    {!! Form::text('nama_pelanggan', null, ['class' => 'form-control', 'id' => "nama_pelanggan"]) !!}
</div>
<div class="form-group">
    <label>No Telepon</label>
    {!! Form::number('no_telepon', null, ['class' => 'form-control', 'id' => "no_telepon"]) !!}
</div>
<div class="form-group">
    <label>No Indentitas</label>
    {!! Form::number('no_identitas', null, ['class' => 'form-control', 'id' => "no_identitas"]) !!}
</div>
<div class="form-group">
    <label>Alamat</label>
    {!! Form::textarea('alamat', null, ['class' => 'form-control', 'id' => "alamat"]) !!}
</div>