<table class="table table-consoned table-bordered">
    <tr>
        <th>Tanggal</th>
        <th class="text-center">Harga</th>
        <th class="text-center">Bayar</th>
    </tr>
    @foreach($data as $key => $row) 
        <tr>
            <td>{{$row->tanggal_bayar}}</td>
            <td class="text-center">{{number_format($row->tarif, 2, ',', '.')}}</td>
            <td class="text-center">{{number_format($row->bayar, 2, ',', '.')}}</td>
        </tr>
    @endforeach
</table>