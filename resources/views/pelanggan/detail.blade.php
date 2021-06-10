<table width="100%">
    <tr>
        <th width="200px"><h6>No Pemasangan</h6></th>
        <th class="text-left">{!! $pemasangan->no_pemasangan !!}</th>
    </tr>
    <tr>
        <th width="200px"><h6>Tarif/bulan</h6></th>
        <th class="text-left">{!! number_format($pemasangan->tarif, 2, ',', '.') !!}</th>
    </tr>
    <tr>
        <th width="200px"><h6>Alamat Pemasangan</h6></th>
        <th class="text-left">{!! $pemasangan->alamat_pemasangan !!}</th>
    </tr>
</table>
<div class="form-group mt-2">
<label><b>Detail Tagihan</b></label>
<table id="table-view" class="table table-consoned table-bordered">
    <thead>
        <tr>
            <th>Tanggal Tagihan</th>
            <th class="text-right">Harga</th>
        </tr>
    </thead>
    <tbody>
    <?php $total = 0 ?>
        @foreach($model as $row)
            <tr>
                <td><b>{!! $row->tanggal_tagihan !!}</b></td>
                <td class="text-right"><b>{!! number_format($row->tarif, 2, ',', '.') !!}</b></td>
            </tr>
        <?php $total += $row->tarif ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total Tagihan</th>
            <th class="text-right">{!! number_format($total, 2, ',', '.') !!}</th>
        </tr>
    </tfoot>
</table>
</div>