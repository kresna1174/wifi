<table width="100%">
    <tr>
        <th>No Pemasangan</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->id !!}</th>
    </tr>
    <tr>
        <th>Tarif/bulan</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->tarif !!}</th>
    </tr>
    <tr>
        <th>Alamat Pemasangan</th>
        <th class="text-center">:</th>
        <th class="text-center">{!! $model->alamat_pemasangan !!}</th>
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
        @foreach($model->tagihan as $row)
            <tr>
                <td><b>{!! $row->tanggal_tagihan !!}</b></td>
                <td class="text-right"><b>{!! $row->tarif !!}</b></td>
            </tr>
        <?php $total += $row->tarif ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total Tagihan</th>
            <th class="text-right">{!! $total !!}</th>
        </tr>
    </tfoot>
</table>
</div>