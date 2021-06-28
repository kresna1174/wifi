<table width="100%">
    <tr>
        <th width="150px"><h6>No Pelanggan</h6></th>
        <th class="text-left">{!! $pelanggan->no_pelanggan !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Nama Pelanggan</h6></th>
        <th class="text-left">{!! $pelanggan->nama_pelanggan !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>No Telepon</h6></th>
        <th class="text-left">{!! $pelanggan->no_telepon !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Jenis Identitas</h6></th>
        @if($pelanggan->identitas == 1)
            <th class="text-left">{!! 'KTP' !!}</th>
        @elseif($pelanggan->identitas == 2)
            <th class="text-left">{!! 'SIM' !!}</th>
        @elseif($pelanggan->identitas == 3)
            <th class="text-left">{!! 'KARTU PELAJAR' !!}</th>
        @endif
    </tr>
    <tr>
        <th width="150px"><h6>No Identitas</h6></th>
        <th class="text-left">{!! $pelanggan->no_identitas !!}</th>
    </tr>
    <tr>
        <th  width="150px"><h6>Alamat</h6></th>
        <th class="text-left">{!! $pelanggan->alamat !!}</th>
        {!! Form::hidden('id_pelanggan', $pelanggan->id, ['id' => 'id_pelanggan']) !!}
    </tr>
</table>
</div>
<table id="table-view" class="table table-condensed table-bordered">
    <thead>
        <tr>
            <th>No Pemasangan</th>
            <th class="text-right">Tarif</th>
            <th class="text-right">Tagihan</th>
            <th class="text-right">Deposit</th>
            <th width="1px"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($pelanggan->pemasangan as $key => $row)
            <tr>
                <td>{!! $row->no_pemasangan !!}</td>
                <td class="text-right">{!! number_format($row->tarif, 2, ',', '.') !!}</td>
                <td class="text-right">{!! number_format($data[$row->no_pemasangan]['total'] ?? 0, 2, ',', '.') !!}</td>
                <td class="text-right">
                    @if (isset($row->saldo->jumlah_deposit)) 
                        {!! number_format($row->saldo->jumlah_deposit, 2, ',', '.') !!}
                    @else
                        0
                    @endif
                </td>
                <td class="text-center nowrap">
                    <button type="button" class="btn btn-info btn-sm" onclick="detail('<?= $row->id ?>', '<?= $pelanggan->id; ?>')">Detail</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="riwayat('<?= $row->id ?>', '<?= $pelanggan->id; ?>')">Histori</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>