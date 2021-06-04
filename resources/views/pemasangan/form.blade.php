<div class="row">
    <div class="col-6">
        <div class='form-group'>
            <label>Pilih Pelanggan</label>
            {!! Form::select('pilih_pelanggan', ['' => 'select', '0' => 'pelanggan lama', '1' => 'pelanggan baru'], null, ['class' => 'form-control', 'id' => 'pilih_pelanggan']) !!}
        </div>
    </div>
    <div class="col-6">
    
<div class='form-group'>
    <label>No Telepon</label>
    {!! Form::text('no_telepon', isset($pelanggan->no_telepon) ? $pelanggan->no_telepon : null, ['class' => 'form-control', 'id' => 'no_telepon']) !!}
</div>
    </div>
</div>
<div class='nama'></div>
<div class='form-group'>
    <label>No Identitas</label>
    {!! Form::text('no_ktp', isset($pelanggan->no_ktp) ? $pelanggan->no_ktp : null, ['class' => 'form-control', 'id' => 'no_ktp']) !!}
</div>
<div class='form-group'>
    <label>Alamat</label>
    {!! Form::textarea('alamat', isset($pelanggan->alamat) ? $pelanggan->alamat : null, ['class' => 'form-control', 'id' => 'alamat']) !!}
</div>
<div class='form-group'>
    <label>Tarif</label>
    {!! Form::text('tarif', isset($pelanggan->tarif) ? $pelanggan->tarif : null, ['class' => 'form-control', 'id' => 'tarif']) !!}
</div>
<div class='form-group'>
    <label>Tanggal Pemasangan</label>
    {!! Form::text('tanggal_pemasangan', isset($pelanggan->tanggal_pemasangan) ? $pelanggan->tanggal_pemasangan : null, ['class' => 'form-control', 'id' => 'tanggal_pemasangan']) !!}
</div>

<div class='form-group'>
    <label>Alamat Pemasangan</label>
    {!! Form::textarea('alamat_pemasangan', isset($pelanggan->alamat_pemasangan) ? $pelanggan->alamat_pemasangan : null, ['class' => 'form-control', 'id' => 'alamat_pemasangan']) !!}
</div>


