<?php

namespace App\Http\Controllers;

use App\tagihan;
use Illuminate\Http\Request;
use App\Libraries\BulanIndo;
use App\pelanggan;
use App\pemasangan;
use Illuminate\Support\Facades\DB;
use Psy\Command\DumpCommand;

class DashboardController extends Controller
{
    public function index() {
        pemasangan::generate();
        $year_list = BulanIndo::year_list();
        $month_list = BulanIndo::month_list();
        $model = pelanggan::with(['pemasangan' => function($data) {
            $data->with('tagihan')->get();
        }])->get();
        $totalTarif = [];
        $totalTagihan = [];
        foreach($model as $row) {
            foreach($row->pemasangan as $pemasangan) {
                if(isset($totalTarif[$pemasangan->no_pemasangan]['total'])) {
                    $totalTarif[$pemasangan->no_pemasangan]['total'] += $pemasangan->tarif;
                } else {
                    $totalTarif[$pemasangan->no_pemasangan]['total'] = $pemasangan->tarif;
                }
                foreach($pemasangan->tagihan as $tagihan) {
                    if(isset($totalTagihan[$pemasangan->no_pemasangan]['total'])) {
                        $totalTagihan[$pemasangan->no_pemasangan]['total'] += $tagihan->sisa_tagihan;
                    } else {
                        $totalTagihan[$pemasangan->no_pemasangan]['total'] = $tagihan->sisa_tagihan;
                    }
                }
            }
        }
        return view('dashboard.index', ['year_list' => $year_list, 'month_list' => $month_list, 'model' => $model, 'totalTarif' => $totalTarif, 'totalTagihan' => $totalTagihan]);
    }
}
