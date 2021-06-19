@extends('layout.main')
@section('content')
<h1 class="page-header">
    Generate Tagihan
</h1>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="container">
            <div class="form-group">
                {!! Form::select('bulan', \App\Libraries\BulanIndo::month_list(), $periode, ['class' => 'form-control', 'id' => 'bulan']) !!}
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

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-consoned table-bordered">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>No Pelanggan</th>
                    <th>Tanggal Tagihan</th>
                    <th>No Pemasangan</th>
                    <th class="text-center">Tarif</th>
                    <th class="text-center">Tagihan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tagihan as $row)
                    <tr>
                        <td>{{$row->nama_pelanggan}}</td>
                        <td>{{$row->no_pelanggan}}</td>
                        <td>{{$row->tanggal_tagihan}}</td>
                        <td>{{$row->no_pemasangan}}</td>
                        <td>{{$row->tarif}}</td>
                        <td class="text-right">{{number_format($total_bayar[$row->no_pemasangan]['total'], 2 ,',', '.')}}</td>
                        <td class="text-center"><a href="<?= route('pembayaran.create') ?>?id_pelanggan=<?= $row->pelanggan_id ?>&no_pemasangan=<?= $row->no_pemasangan ?>" role="button" class="btn btn-primary" >Bayar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="page-content"></div>
@endsection

@section('script')
    <script>
        function filter() {
            document.location.href='<?= route('tagihan') ?>?periode=' + $('#tahun').val() + '-' + $('#bulan').val()
        }

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
        }

        function pop(data) {
            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
        }
    </script>
@endsection