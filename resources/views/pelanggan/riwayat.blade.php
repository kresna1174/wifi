<table class="table table-condensed table-bordered">
    <tr>
        <th>Tanggal</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Bayar</th>
    </tr>
    @foreach($data as $key => $row) 
        <tr>
            <td>{{$row->tanggal_bayar}}</td>
            <td class="text-right">{{number_format($row->tarif, 2, ',', '.')}}</td>
            <td class="text-right">{{number_format($row->bayar, 2, ',', '.')}}</td>
        </tr>
    @endforeach
</table>