<table width="100%">
    <tr>
        <th>Nama Pelanggan</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->nama_pelanggan !!}</th>
    </tr>
    <tr>
        <th>No Telepon</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->no_telepon !!}</th>
    </tr>
    <tr>
        <th>No Identitas</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->no_identitas !!}</th>
    </tr>
    <tr>
        <th>Alamat</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->alamat !!}</th>
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
                <td>{!! $row->id !!}</td>
                <td>{!! $row->tarif !!}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-sm" onclick="detail('<?= $row->id ?>', '<?= $model->id; ?>')">Detail</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="riwayat('<?= $row->id ?>', '<?= $model->id; ?>')">Histori</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>