@extends('layout.main')
@section('content')
<h1 class="page-header">
    Generate Tagihan
<div class="pull-right">
        <div class="form-inline">
            <div class="form-group">
                <button type="button" id="btn-create" class="btn btn-primary" onclick="generate()">Generate Tagihan</button>
            </div>
        </div>
    </div>
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
                        html +=                     '<th class="text-center">Tagihan</th>'
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
                        html +=                     '<th class="text-center">Tagihan</th>'
                        html +=                     '<th></th>'
                        html +=                 '</tr>'
                        html +=             '</thead>'
                        html +=             '<tbody>'
                        $.each(response, function(data, row) {
                            $.each(row.tagihan, function(a, result) {
                                html +=             '<tr>'
                                html +=                 '<td>'+result.nama_pelanggan+'</td>'
                                html +=                 '<td>'+result.no_pelanggan+'</td>'
                                html +=                 '<input type="hidden" id="pelanggan_id" value="'+result.pelanggan_id+'">'
                                html +=                 '<input type="hidden" id="no_pemasangan" value="'+row.no_pemasangan+'">'
                                html +=                 '<td>'+result.tanggal_tagihan+'</td>'
                                html +=                 '<td>'+row.no_pemasangan+'</td>'
                                html +=                 '<td class="text-right">'+pop(row.tarif)+'</td>'
                                html +=                 '<td><p class="templates"></p></td>'
                                html +=                 '<td class="text-center"><a href="<?= route('pembayaran.create') ?>?id_pelanggan='+result.pelanggan_id+'&no_pemasangan='+row.no_pemasangan+'" role="button" class="btn btn-primary">bayar</a></td>'
                                html +=             '</tr>'
                            })
                        });
                        html +=             '</tbody>'
                        html +=         '</table>'
                        html +=     '</div>'
                        html += '</div>'
                    }
                    $('#page-content').html(html)
                    $.ajax({
                        url: '<?= route('tagihan.get_total') ?>?periode=' + $('#tahun').val() + '-' + $('#bulan').val(),
                        success: function(response) {
                            $.each(response, function(a, b) {
                                $('.templates').text(pop(b.total))
                            })
                        }
                    })
                }
            })
        }

        function generate() {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Apakah Anda Ingin Mengenerate Tagihan ?',
                icon: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        url: '<?= route('tagihan.generate') ?>',
                        success: function(response) {
                            if(response.success) {
                                $.growl.notice({
                                    title : 'success',
                                    message : response.message
                                });
                            } else {
                                $.growl.notice({
                                    title : 'failed',
                                    message : response.message
                                });
                            }
                        }
                    })
                }
            })
        }

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
        }
    </script>
@endsection