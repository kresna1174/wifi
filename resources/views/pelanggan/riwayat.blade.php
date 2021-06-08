<table class="table table-consoned table-bordered">
    <tr>
        <th>Tanggal</th>
        <th class="text-center">Harga</th>
        <th class="text-center">Bayar</th>
    </tr>
    @foreach($data as $key => $row) 
        <tr>
            <td>{{$row->tanggal_bayar}}</td>
            <td class="text-center">{{$row->tarif}}</td>
            <td class="text-center">{{$row->bayar}}</td>
        </tr>
    @endforeach
</table>