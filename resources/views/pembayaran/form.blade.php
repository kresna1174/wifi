<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label>Pelanggan</label>
            {!! Form::select('nama_pelanggan', ['' => 'select'] + $pelanggan->toArray(), null, ['class' => 'form-control', 'id' => 'nama_pelanggan']) !!}
        </div>
        <div class="form-group">
            <label>No Pemasangan</label>
            {!! Form::select('no_pemasangan', ['' => 'select'] + \App\pemasangan::pluck('id', 'id')->toArray(), null, ['class' => 'form-control', 'id' => 'no_pemasangan']) !!}
        </div>
    </div>
    <div class="col-6">
        <label>Alamat Pemasangan</label>
        {!! Form::textarea('alamat_pemasangan', null, ['class' => 'form-control', 'id' => 'alamat_pemasangan', 'readonly']) !!}
        {!! Form::hidden('id_tagihan', null, ['class' => 'form-control', 'id' => 'id_tagihan']) !!}
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
            {!! Form::number('total_bayar', null, ['class' => 'form-control text-right', 'id' => 'total_bayar', 'readonly']) !!}
        </div>
        <div class="form-group">
            <label>Tunai</label>
            {!! Form::number('bayar', null, ['class' => 'form-control text-right', 'id' => 'bayar', 'onkeyup' => 'unblock()']) !!}
            {!! Form::hidden('deposit', null, ['class' => 'form-control text-right', 'id' => 'deposit']) !!}
        </div>
    </div>
</div>