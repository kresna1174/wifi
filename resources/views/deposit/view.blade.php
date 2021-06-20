<table width="100%">
    <tr>
        <th width="150px"><h6>Nama Pelanggan</h6></th>
        <th class="text-left">{!! $model->nama_pelanggan !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>No Pemasangan</h6></th>
        <th class="text-left">{!! $model->no_pemasangan->no_pemasangan !!}</th>
    </tr>
    @foreach($data as $row)
    <tr>
        <th width="150px"><h6>Jumlah Deposit</h6></th>
        <th class="text-left">{!! number_format($row->jumlah_deposit, 2, ',', '.') !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Tanggal</h6></th>
        <th class="text-left">{!! $row->tanggal !!}</th>
    </tr>
    @endforeach
</table>