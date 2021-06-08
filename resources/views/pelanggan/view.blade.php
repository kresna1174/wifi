<table width="100%">
    <tr>
        <th width="150px"><h6>No Pelanggan</h6></th>
        <th class="text-left">{!! $model->no_pelanggan !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Nama Pelanggan</h6></th>
        <th class="text-left">{!! $model->nama_pelanggan !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>No Telepon</h6></th>
        <th class="text-left">{!! $model->no_telepon !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Jenis Identitas</h6></th>
        @if($model->identitas == 1)
            <th class="text-left">{!! 'KTP' !!}</th>
        @elseif($model->identitas == 2)
            <th class="text-left">{!! 'SIM' !!}</th>
        @elseif($model->identitas == 3)
            <th class="text-left">{!! 'KARTU PELAJAR' !!}</th>
        @endif
    </tr>
    <tr>
        <th width="150px"><h6>No Identitas</h6></th>
        <th class="text-left">{!! $model->no_identitas !!}</th>
    </tr>
    <tr>
        <th  width="150px"><h6>Alamat</h6></th>
        <th class="text-left">{!! $model->alamat !!}</th>
        {!! Form::hidden('id_pelanggan', $model->id, ['id' => 'id_pelanggan']) !!}
    </tr>
</table>
<table id="table-view" class="table table-consoned table-bordered">
    <thead>
        <tr>
            <th>No Pemasangan</th>
            <th>Tarif</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php $total = 0 ?>
        @foreach($model->pemasangan as $key => $row)
            <tr>
                <td>{!! $row->no_pemasangan !!}</td>
                <td>{!! number_format($row->tagihan, 2, ',', '.') !!}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-sm" onclick="detail('<?= $row->id ?>', '<?= $model->id; ?>')">Detail</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="riwayat('<?= $row->id ?>', '<?= $model->id; ?>')">Histori</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>