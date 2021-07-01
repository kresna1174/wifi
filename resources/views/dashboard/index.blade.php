@extends('layout.main')
@section('content')
<h1 class="page-header">
    Dahsboard
</h1>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-blue">
            <div class="stats-icon"><i class="fa fa-wifi"></i></div>
            <div class="stats-info">
                <h4>TOTAL PEMASANGAN</h4>
                <p>{!! $pemasangan->count() !!}</p>	
            </div>
            <div class="stats-link">
                <a href="{!! route('pemasangan') !!}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-green">
            <div class="stats-icon"><i class="fa fa-dollar-sign"></i></div>
            <div class="stats-info">
                <h4>TOTAL TAGIHAN PER BULAN</h4>                
                @if (isset($totalMonthlyInvoice[0]))
                    <p>{!! number_format($totalMonthlyInvoice[0]->total_tagihan, 2 ,',', '.') !!}</p>
                @else
                    <p>0</p>
                @endif                  
            </div>
            <div class="stats-link">
                <a href="{!! route('pemasangan') !!}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-orange">
            <div class="stats-icon"><i class="fa fa-receipt"></i></div>
            <div class="stats-info">
                <h4>TOTAL TAGIHAN</h4>
                @if (isset($totalTagihan[0]))
                    <p>{!! number_format($totalTagihan[0]->sisa_tagihan, 2 ,',', '.') !!}</p>
                @else
                    <p>0</p>
                @endif                
            </div>
            <div class="stats-link">
                <a href="{!! route('tagihan') !!}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-red">
            <div class="stats-icon"><i class="fa fa-receipt"></i></div>
            <div class="stats-info">
                <h4>KOLEKTIF RATE</h4>
                @if (isset($collectiveRate[0]))
                    <p>{!! $collectiveRate[0]->rate !!}%</p>
                @else
                    <p>0</p>
                @endif                
            </div>
            <div class="stats-link">
                <a href="{!! route('tagihan') !!}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection