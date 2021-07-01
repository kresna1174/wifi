<?php
    if ($year == date('Y')) {
        $lastMonth = date('m');
    } else {
        $lastMonth = 12;
    }
?>
@extends('layout.main')
@section('content')
<h1 class="page-header">
    Buku Tagihan
</h1>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>No Pelanggan</th>
                    <th>Pelanggan</th>
                    <th>Pemasangan</th>
                    <th class="text-center" width="1"><a href="{!! route('tagihan.book', ['year' => ($year-1)]) !!}">{!! ($year-1) !!} ></a></th>
                    @for($i = 1; $i <= $lastMonth; $i++)
                        <th class="text-center" width="1"><?= App\Libraries\BulanIndo::month_list(null)[substr('00'.$i, -2)] ?></th>    
                    @endfor
                    @if ($year < date('Y'))
                        <th class="text-center" width="1"><a href="{!! route('tagihan.book', ['year' => ($year+1)]) !!}">< {!! ($year+1) !!}</a></th>
                    @endif
                    <th width="1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasangan as $rPemasangan)
                <tr>
                    <td>{!! $rPemasangan->infoPelanggan->no_pelanggan !!}</td>
                    <td>{!! $rPemasangan->infoPelanggan->nama_pelanggan !!}</td>
                    <td>
                        <a href="#javascript:void(0)" onclick="detail({!! $rPemasangan->id !!}, {!! $rPemasangan->infoPelanggan->id !!})">{!! $rPemasangan->no_pemasangan !!}</a><br>
                        <small class="text-primary">{!! ($rPemasangan->alamat_pemasangan) !!}</small>
                    </td>
                    <td class="text-right nowrap">
                        @if (isset($prevTagihan[$rPemasangan->id]))
                            {{ number_format($prevTagihan[$rPemasangan->id], 2 ,',', '.') }}
                        @else
                            0
                        @endif
                    </td>
                    @for($i = 1; $i <= $lastMonth; $i++)
                        <td class="text-right nowrap">                            
                            @if (isset($currentTagihan[$rPemasangan->id][($year.'-'.substr('00'.$i, -2))]))
                                {{ number_format($currentTagihan[$rPemasangan->id][($year.'-'.substr('00'.$i, -2))]->sisa_tagihan, 2 ,',', '.') }}
                            @else
                                0
                            @endif
                        </td>    
                    @endfor
                    @if ($year < date('Y'))
                        <td class="text-right nowrap">
                            @if (isset($nextTagihan[$rPemasangan->id]))
                                {{ number_format($nextTagihan[$rPemasangan->id], 2 ,',', '.') }}
                            @else
                                0
                            @endif
                        </td>
                    @endif
                    <td class="nowrap">
                        <a href="{!! route('pembayaran.create', ['id_pelanggan' => $rPemasangan->id_pelanggan, 'no_pemasangan' => $rPemasangan->no_pemasangan]) !!}" class="btn btn-primary btn-sm" target="blank">Bayar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    function detail(id, id_pelanggan) {
        $.ajax({
            url: '<?= route('pelanggan.detail') ?>?id_pemasangan='+id + '&id_pelanggan=' + id_pelanggan,
            success: function(response) {
                bootbox.dialog({
                    size: 'large',
                    title: 'Detail Pemasangan',
                    message: response
                })
            }
        })
    }
</script>
@endsection