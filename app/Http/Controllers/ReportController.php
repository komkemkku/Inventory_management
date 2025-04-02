<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * แสดงฟอร์มกรองรายงานคำสั่งซื้อ
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * แสดงข้อมูลรายงานคำสั่งซื้อในช่วงวันที่ที่เลือก
     */
    public function show(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Query เฉพาะคำสั่งซื้อที่สถานะเป็น pending
        $orders = DB::table('h_order')
            ->join('d_order', 'h_order.order_id', '=', 'd_order.order_id')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->join('goods_name', 'd_order.good_id', '=', 'goods_name.goods_id')
            ->whereDate('d_order.ord_date', '>=', $startDate)
            ->whereDate('d_order.ord_date', '<=', $endDate)
            ->where('d_order.status', 'pending')
            ->select(
                'h_order.order_id as order_number',
                'cus_name.cus_id as customer_code',
                'cus_name.cus_name as customer_name',
                'goods_name.goods_id as product_code',
                'goods_name.goods_name as product_name',
                'd_order.ord_date',
                'd_order.fin_date as delivery_date',
                'd_order.amount',
                'goods_name.cost_unit as unit_price',
                DB::raw('(d_order.amount * goods_name.cost_unit) as total_price')
            )
            ->get();

        // คำนวณจำนวนรวมทั้งหมดและราคารวมทั้งหมด
        $totalAmount = $orders->sum('amount');
        $totalPrice = $orders->sum('total_price');

        return view('reports.index', compact('orders', 'totalAmount', 'totalPrice', 'startDate', 'endDate'));
    }
}
