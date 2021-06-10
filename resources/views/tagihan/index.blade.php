@extends('layout.main')
@section('content')
<h1 class="page-header">
    Generate Tagihan
</h1>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="container">
            <div class="form-group">
                {!! Form::select('bulan', \App\Libraries\BulanIndo::month_list(), date('m'), ['class' => 'form-control', 'id' => 'bulan']) !!}
            </div>
            <div class="form-group">
                {!! Form::select('tahun', \App\Libraries\BulanIndo::year_list(), date('Y'), ['class' => 'form-control', 'id' => 'tahun']) !!}
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-success" onclick="filter()">Filter</button>
            </div>
        </div>
    </div>
</div>

<div id="page-content"></div>
@endsection

@section('script')
    <script>
        function filter() {
            $.ajax({
                url: '<?= route('tagihan.get_tagihan') ?>?periode=' + $('#tahun').val() + '-' + $('#bulan').val(),
                success: function(response) {
                    let html = '';
                    if(!$.trim(response)) {
                        html += '<div class="panel panel-default">'
                        html +=     '<div class="panel-body">'
                        html +=         '<table width="100%" class="table table-consoned table-bordered">'
                        html +=             '<thead>'
                        html +=                 '<tr>'
                        html +=                     '<th>Nama Pelanggan</th>'
                        html +=                     '<th>No Pelanggan</th>'
                        html +=                     '<th>Tanggal Tagihan</th>'
                        html +=                     '<th>No Pemasangan</th>'
                        html +=                     '<th class="text-center">Tarif</th>'
                        html +=                     '<th class="text-center">Sisa Tagihan</th>'
                        html +=                     '<th></th>'
                        html +=                 '</tr>'
                        html +=             '</thead>'
                        html +=             '<tbody>'
                        html +=             '<td colspan="7" class="text-center">Data Tidak di Temukan</td>'
                        html +=             '</tbody>'
                        html +=         '</table>'
                        html +=     '</div>'
                        html += '</div>'
                    } else {
                        html += '<div class="panel panel-default">'
                        html +=     '<div class="panel-body">'
                        html +=         '<table width="100%" class="table table-consoned table-bordered">'
                        html +=             '<thead>'
                        html +=                 '<tr>'
                        html +=                     '<th>Nama Pelanggan</th>'
                        html +=                     '<th>No Pelanggan</th>'
                        html +=                     '<th>Tanggal Tagihan</th>'
                        html +=                     '<th>No Pemasangan</th>'
                        html +=                     '<th class="text-center">Tarif</th>'
                        html +=                     '<th class="text-center">Sisa Tagihan</th>'
                        html +=                     '<th></th>'
                        html +=                 '</tr>'
                        html +=             '</thead>'
                        html +=             '<tbody>'
                        $.each(response, function(data, row) {
                            html +=             '<td>'+row.nama_pelanggan+'</td>'
                            html +=             '<td>'+row.no_pelanggan+'</td>'
                            html +=             '<input type="hidden" id="pelanggan_id" value="'+row.pelanggan_id+'">'
                            html +=             '<input type="hidden" id="no_pemasangan" value="'+row.no_pemasangan+'">'
                            html +=             '<td>'+row.tanggal_tagihan+'</td>'
                            html +=             '<td>'+row.no_pemasangan+'</td>'
                            html +=             '<td class="text-right">'+row.tarif+'</td>'
                            html +=             '<td class="text-right">'+row.sisa_tagihan+'</td>'
                            html +=             '<td class="text-center"><button type="button" class="btn btn-primary" onclick="bayar()">bayar</button></td>'
                        });
                        html +=             '</tbody>'
                        html +=         '</table>'
                        html +=     '</div>'
                        html += '</div>'
                    }
                    $('#page-content').html(html)
                }
            })
        }

        function bayar(no_pemasangan) {
            document.location.href='<?= route('pembayaran.create') ?>?id_pelanggan=' + $('#pelanggan_id').val() + '&no_pemasangan=' + $('#no_pemasangan').val()
        }
    </script>
@endsection