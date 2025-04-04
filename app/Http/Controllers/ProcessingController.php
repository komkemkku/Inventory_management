<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessingController extends Controller
{
    /**
     * แสดงฟอร์มสำหรับเลือกช่วงวันที่
     */
    public function index()
    {
        return view('processings.index');
    }

    /**
     * ประมวลผลข้อมูลตามช่วงวันที่และโยกย้ายไปยัง m_order
     */
    public function process(Request $request)
    {
        // ตรวจสอบค่าที่ได้รับจากฟอร์ม
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // รับค่าเริ่มต้นและสิ้นสุดวันที่
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // ดึงข้อมูลเฉพาะที่มีสถานะ shipped
        $orders = DB::table('h_order')
            ->join('d_order', 'h_order.order_id', '=', 'd_order.order_id')
            ->whereBetween('d_order.fin_date', [$startDate, $endDate])
            ->where('d_order.status', 'shipped')
            ->select(
                'h_order.order_id',
                'h_order.cus_id',
                'd_order.good_id',
                'h_order.order_date as doc_date',
                'd_order.ord_date',
                'd_order.fin_date',
                DB::raw('NOW() as sys_date'),
                'd_order.amount',
                'd_order.tot_prc as cost_tot'
            )
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลในช่วงวันที่ที่เลือก');
        }

        // เพิ่มข้อมูลเข้าใน m_order
        foreach ($orders as $order) {
            DB::table('m_order')->insert([
                'cus_id'   => $order->cus_id,
                'goods_id' => $order->good_id,
                'doc_date' => $order->doc_date,
                'ord_date' => $order->ord_date,
                'fin_date' => $order->fin_date,
                'sys_date' => $order->sys_date,
                'amount'   => $order->amount,
                'cost_tot' => $order->cost_tot,
            ]);
        }

        // ลบข้อมูลเฉพาะสถานะ shipped ใน d_order
        DB::table('d_order')
            ->whereBetween('fin_date', [$startDate, $endDate])
            ->where('status', 'shipped')
            ->delete();

        // ลบข้อมูลใน h_order ที่ไม่มีรายการสินค้าคงเหลือใน d_order
        DB::table('h_order')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('d_order')
                    ->whereColumn('h_order.order_id', 'd_order.order_id');
            })
            ->delete();

        return back()->with('success', 'ประมวลผลข้อมูลและลบคำสั่งซื้อที่ไม่มีรายการสำเร็จเรียบร้อยแล้ว!');
    }
}
