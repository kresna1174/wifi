<table width="100%">
    <tr>
        <th width="150px"><h6>Nama Pelanggan</h6></th>
        <th class="text-left">{!! $model->nama_pelanggan !!}</th>
    </tr>
    <tr>
    @foreach($data as $row)
        <th width="150px"><h6>Jumlah Deposit</h6></th>
        <th class="text-left">{!! $row->jumlah_deposit !!}</th>
    </tr>
    <tr>
        <th width="150px"><h6>Tanggal</h6></th>
        <th class="text-left">{!! $row->tanggal !!}</th>
    </tr>
    @endforeach
</table>