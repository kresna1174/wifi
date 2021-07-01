<?php

namespace App\Http\Controllers;
use App\pemasangan;
use App\tagihan;

class DashboardController extends Controller
{
    public function index() {
        pemasangan::generate();      
        $pemasangan = pemasangan::where('deleted', 0)
            ->get();  
        $totalMonthlyInvoice = pemasangan::getTotalMonthlyInvoice();
        $totalTagihan = tagihan::getTotalTagihan();
        $collectiveRate = tagihan::getCollectiveRate();
        return view('dashboard.index', compact('pemasangan', 'totalTagihan', 'totalMonthlyInvoice', 'collectiveRate'));
    }
}
