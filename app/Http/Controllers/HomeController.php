<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\BudgetInsentif;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transaksi = Transaksi::all();
        $totalInsentif = 0;
        foreach ($transaksi as $item) {
            if ($item->produk && $item->produk->produk_insentif !== null) {
                $totalInsentif += $item->produk->produk_insentif;
            }
        }
        $budgetInsentif = BudgetInsentif::first();
        $totalBudget = $budgetInsentif ? $budgetInsentif->total_insentif : 0;
        $sisaBudget = $totalBudget - $totalInsentif;

        $today = Carbon::today()->format('Y-m-d');
        $kaliTransaksi = Transaksi::withTrashed()
            ->with('produk')
            ->whereDate('tanggal_transaksi', $today)
            ->count();


        $groupedTransaksi = $transaksi->groupBy(function ($item) {
            return Carbon::today()->format('Y-m-d');
        });
        $totalsPerDate = $groupedTransaksi->map(function ($items) {
            $totalPenjualan = $items->sum(function ($item) {
                return $item->produk ? $item->produk->produk_harga_akhir : 0;
            });
            $totalInsentif = $items->sum(function ($item) {
                return $item->produk ? $item->produk->produk_insentif : 0;
            });

            return [
                'totalPenjualan' => $totalPenjualan,
                'totalInsentif' => $totalInsentif,
            ];
        });
        $nominalTransaksi = $totalsPerDate->sum('totalPenjualan');

        return view('supvis.home', compact('totalInsentif', 'sisaBudget', 'kaliTransaksi', 'nominalTransaksi'));
    }
}
