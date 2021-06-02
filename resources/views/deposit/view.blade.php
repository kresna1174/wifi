<table width="100%">
    <tr>
        <th>Nama Pelanggan</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->nama_pelanggan !!}</th>
    </tr>
    <tr>
    @foreach($data as $row)
        <th>Jumlah Deposit</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $row->jumlah_deposit !!}</th>
    </tr>
    <tr>
        <th>Tanggal</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $row->tanggal !!}</th>
    </tr>
    @endforeach
</table>