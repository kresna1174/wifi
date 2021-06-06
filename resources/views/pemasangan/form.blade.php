<div class="row">
    <div class="col-6">
        <div class='form-group'>
            <label>Pilih Pelanggan</label>
            <div class="pilih_pelanggan"></div>
            {!! Form::select('pilih_pelanggan', ['' => 'select', '1' => 'pelanggan lama', '2' => 'pelanggan baru'], null, ['class' => 'form-control', 'id' => 'pilih_pelanggan', 'onchange' => 'pilih()']) !!}
        </div>
    </div>
    <div class="col-6">
        <div class='form-group'>
            <label>No Telepon</label>
            {!! Form::text('no_telepon', isset($pelanggan->no_telepon) ? $pelanggan->no_telepon : null, ['class' => 'form-control', 'id' => 'no_telepon']) !!}
        </div>
    </div>
</div>

<div class='form-group'>
    <label>Nama Pelanggan</label>
    <div class="nama_pelanggan"></div>
    <?php if(isset($pelanggan->nama_pelanggan)) { ?>
        {!! Form::select('nama_pelanggan', ['' => 'select'] + $pelanggan->nama_pelanggan->toArray(), $pelanggan->id_pelanggan, ['class' => 'form-control', 'id' => 'nama_pelanggan', 'onchange' => 'change_pelanggan()']) !!}
    <?php } else { ?>
        {!! Form::text('nama_pelanggan', null, ['class' => 'form-control', 'id' => 'nama_pelanggan']) !!}
    <?php } ?>
</div>
<div class='form-group'>
    <label>No Identitas</label>
    {!! Form::number('no_identitas', isset($pelanggan->no_identitas) ? $pelanggan->no_identitas : null, ['class' => 'form-control', 'id' => 'no_identitas']) !!}
</div>
<div class='form-group'>
    <label>Alamat</label>
    {!! Form::textarea('alamat', isset($pelanggan->alamat) ? $pelanggan->alamat : null, ['class' => 'form-control', 'id' => 'alamat']) !!}
</div>
<div class='form-group'>
    <label>Tarif</label>
    {!! Form::number('tarif', isset($pelanggan->tarif) ? $pelanggan->tarif : null, ['class' => 'form-control', 'id' => 'tarif']) !!}
</div>
<div class='form-group'>
    <label>Tanggal Pemasangan</label>
    {!! Form::text('tanggal_pemasangan', isset($pelanggan->tanggal_pemasangan) ? $pelanggan->tanggal_pemasangan : null, ['class' => 'form-control', 'id' => 'tanggal_pemasangan', 'autocomplete' => 'off']) !!}
</div>

<div class='form-group'>
    <label>Alamat Pemasangan</label>
    {!! Form::textarea('alamat_pemasangan', isset($pelanggan->alamat_pemasangan) ? $pelanggan->alamat_pemasangan : null, ['class' => 'form-control', 'id' => 'alamat_pemasangan']) !!}
</div>


