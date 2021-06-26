<table class="table table-consoned table-bordered">
    <tr>
        <th class="text-center">Tanggal</th>
        <th class="text-center">Harga</th>
        <th class="text-center">Bayar</th>
    </tr>
    @foreach($data as $key => $row) 
        <tr>
            <td>{{$row->tanggal_bayar}}</td>
            <td class="text-center">{{number_format($row->tarif, 2, ',', '.')}}</td>
            <td class="text-center">{{number_format($row->jumlah_bayar, 2, ',', '.')}}</td>
        </tr>
    @endforeach
</table>