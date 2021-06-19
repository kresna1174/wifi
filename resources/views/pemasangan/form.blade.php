<div class='form-group'>
    <label>No Pemasangan</label>
    {!! Form::text('no_pemasangan', $no_pemasangan ?? $pemasangan->no_pemasangan, ['class' => 'form-control', 'id' => 'no_pemasangan', 'readonly']) !!}
</div>
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
            {!! Form::number('no_telepon', isset($rs_pemasangan->no_telepon) ? $rs_pemasangan->no_telepon : null, ['class' => 'form-control', 'id' => 'no_telepon']) !!}
        </div>
    </div>
</div>
<div class='form-group'>
    <label>Nama Pelanggan</label>
    <div class="nama_pelanggan"></div>
    <?php if(isset($pemasangan->nama_pelanggan)) { ?>
        {!! Form::select('nama_pelanggan', ['' => 'select'] + $pemasangan->nama_pelanggan->toArray(), $pemasangan->id_pelanggan, ['class' => 'form-control', 'id' => 'nama_pelanggan', 'onchange' => 'change_pelanggan()']) !!}
    <?php } else { ?>
        {!! Form::text('nama_pelanggan', null, ['class' => 'form-control', 'id' => 'nama_pelanggan']) !!}
    <?php } ?>
</div>
<div class='form-group'>
    <label>No Pelanggan</label>
    {!! Form::text('no_pelanggan', $rs_pemasangan->no_pelanggan ?? $pelanggan->no_pelanggan, ['class' => 'form-control', 'id' => 'no_pelanggan', 'readonly']) !!}
</div>
<div class='form-group'>
    <label>Jenis Identitas</label>
    {!! Form::select('identitas', ['' => 'select', '1' => 'KTP', '2' => 'SIM', '3' => 'KARTU PELAJAR'], $rs_pemasangan->identitas ?? null, ['class' => 'form-control', 'id' => 'identitas']) !!}
</div>
<div class='form-group'>
    <label>No Identitas</label>
    {!! Form::number('no_identitas', isset($rs_pemasangan->no_identitas) ? $rs_pemasangan->no_identitas : null, ['class' => 'form-control', 'id' => 'no_identitas']) !!}
</div>
<div class='form-group'>
    <label>Alamat</label>
    {!! Form::textarea('alamat', isset($rs_pemasangan->alamat) ? $rs_pemasangan->alamat : null, ['class' => 'form-control', 'id' => 'alamat']) !!}
</div>
<div class='form-group'>
    <label>Tarif</label>
    {!! Form::text('tarif', isset($rs_pemasangan->tarif) ? $rs_pemasangan->tarif : null, ['class' => 'form-control text-right', 'id' => 'tarif']) !!}
</div>
<div class="row">
    <div class="col-6">
        <div class='form-group'>
            <label>Tanggal Pemasangan</label>
            {!! Form::text('tanggal_pemasangan', isset($rs_pemasangan->tanggal_pemasangan) ? $rs_pemasangan->tanggal_pemasangan : null, ['class' => 'form-control', 'id' => 'tanggal_pemasangan', 'autocomplete' => 'off']) !!}
        </div>
    </div>
    <div class="col-6">
        <div class='form-group'>
            <label>Tanggal Tagihan</label>
            {!! Form::select('tanggal_tagihan', $tanggal, isset($rs_pemasangan->tanggal_tagihan) ? $rs_pemasangan->tanggal_tagihan : null, ['class' => 'form-control', 'id' => 'tanggal_tagihan', 'autocomplete' => 'off']) !!}
        </div>
    </div>
</div>
<div class='form-group'>
    <label>Alamat Pemasangan</label>
    {!! Form::textarea('alamat_pemasangan', isset($rs_pemasangan->alamat_pemasangan) ? $rs_pemasangan->alamat_pemasangan : null, ['class' => 'form-control', 'id' => 'alamat_pemasangan']) !!}
</div>


