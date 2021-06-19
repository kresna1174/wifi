@extends('layout.main')
@section('content')
<h1 class="page-header">
    Dahsboard
</h1>
<div class="panel panel-default">
    <div class="panel-body">
        <table id="table" class="table table-consoned table-bordered">
            <?php 
                $total_tarif = 0;
                $total_tagihan = 0;
            ?>
            @foreach($model as $row)
            <tr data-tt-id="{!! $row->no_pelanggan !!}">
                <td colspan="5">{{$row->nama_pelanggan}}</td>
            </tr>
                @foreach($row->pemasangan as $pemasangan)
                    <tr data-tt-id="{!! $pemasangan->no_pemasangan !!}" data-tt-parent-id="{!! $row->no_pelanggan !!}">
                        <td colspan="5">{!! $pemasangan->no_pemasangan !!}</td>
                    </tr>
                    <tr data-tt-id="{!! $row->no_pelanggan.'-'.$pemasangan->no_pemasangan !!}" data-tt-parent-id="{!! $pemasangan->no_pemasangan !!}" class="bg-dark text-light">
                        <td>Tanggal Tagihan</td>
                        <td class="text-center">Status Bayar</td>
                        <td class="text-center">Tarif</td>
                        <td class="text-center" colspan="2">Tagihan</td>
                    </tr>
                    @foreach($pemasangan->tagihan as $tagihan)
                        <tr data-tt-id="{!! $row->no_pelanggan.'-'.$pemasangan->no_pemasangan !!}" data-tt-parent-id="{!! $pemasangan->no_pemasangan !!}">
                            <td>{!! \App\Libraries\BulanIndo::tanggal_indo($tagihan->tanggal_tagihan) !!}</td>
                            <td class="text-center">
                                @if($tagihan->status_bayar == 1)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td class="text-right">{!! number_format($pemasangan->tarif, 2, ',', '.') !!}</td>
                            <td class="text-center" colspan="2">{!! number_format($tagihan->sisa_tagihan, 2, ',', '.') !!}</td>
                            <?php $total_tarif += $pemasangan->tarif ?>
                            <?php $total_tagihan += $tagihan->sisa_tagihan ?>
                        </tr>
                    @endforeach
                        @if($pemasangan->id != $tagihan->id_pemasangan)
                            <tr data-tt-id="{!! $row->no_pelanggan.'-'.$pemasangan->no_pemasangan !!}" data-tt-parent-id="{!! $pemasangan->no_pemasangan !!}">
                                <td colspan="5" class="text-center">Data Tidak di Temukan</td>
                            </tr>
                        @else
                            <tr data-tt-id="{!! $row->no_pelanggan.'-'.$pemasangan->no_pemasangan !!}" data-tt-parent-id="{!! $pemasangan->no_pemasangan !!}">
                                <td colspan="3"><b>Total</b></td>
                                <td class="text-right"><b>{!! number_format($totalTagihan[$pemasangan->no_pemasangan]['total'], 2, ',', '.') !!}</b></td>
                                @if($totalTagihan[$pemasangan->no_pemasangan]['total'] == 0)
                                    <td class="text-center nowrap"><button type="button" class="btn btn-success" onclick="detail('<?= $pemasangan->id ?>', '<?= $row->id ?>')">Detail</button></td>
                                @else
                                    <td class="text-center nowrap"><a href="<?= route('pembayaran.create') ?>?id_pelanggan=<?= $row->id ?>&no_pemasangan=<?= $pemasangan->no_pemasangan ?>" role="button" class="btn btn-primary">Bayar</a></td>
                                @endif
                            </tr>
                        @endif
                @endforeach
            @endforeach
        </table>
    </div>
</div>
@endsection

@section('script')
    <script>
        $('#table').treetable({
            expandable: true
        });

        function detail(id_pemasangan, id_pelanggan) {
            $.ajax({
                url: '<?= route('pelanggan.riwayat') ?>?id_pemasangan='+id_pemasangan + '&id_pelanggan=' + id_pelanggan,
                success: function(response) {
                    bootbox.dialog({
                        size: 'large',
                        title: 'Detail Pembayaran',
                        message: response
                    })
                }
            })
        }
    </script>
@endsection