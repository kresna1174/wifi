@extends('layout.main')
@section('content')
<h1 class="page-header">
        Create Pembayaran
    </h1>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Pelanggan</label>
                    {!! Form::select('nama_pelanggan', ['' => 'select'], null, ['class' => 'form-control', 'id' => 'nama_pelanggan']) !!}
                </div>
                <div class="form-group">
                    <label>No Pemasangan</label>
                    {!! Form::select('no_pemasangan', ['' => 'select'], null, ['class' => 'form-control', 'id' => 'no_pemasangan']) !!}
                </div>
            </div>
            <div class="col-6">
                <label>Alamat Pemasangan</label>
                {!! Form::textarea('alamat_pemasangan', null, ['class' => 'form-control', 'id' => 'alamat_pemasangan']) !!}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <table class="table table-bordered table-consoned" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tarif</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Total Bayar</label>
                    {!! Form::text('total_bayar', null, ['class' => 'form-control', 'id' => 'total_bayar', 'readonly']) !!}
                </div>
                <div class="form-group">
                    <label>Tunai</label>
                    {!! Form::text('total_bayar', null, ['class' => 'form-control', 'id' => 'total_bayar']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection