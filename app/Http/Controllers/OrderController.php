<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource (แสดงรายการคำสั่งซื้อ).
     */
    public function index()
    {
        $orders = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->leftJoin('d_order', 'h_order.order_id', '=', 'd_order.order_id')
            ->select(
                'h_order.order_id',
                'h_order.cus_id',
                'cus_name.cus_name',
                DB::raw('COUNT(d_order.d_id) as count_item'),
                DB::raw('SUM(d_order.amount) as total_amount')
            )
            ->groupBy('h_order.order_id', 'h_order.cus_id', 'cus_name.cus_name')
            ->orderBy('h_order.order_id', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource (แสดงฟอร์มเพิ่มข้อมูลการสั่งซื้อ).
     */
    public function create()
    {
        $customers = DB::table('cus_name')->get();

        return view('orders.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage (บันทึกข้อมูลคำสั่งซื้อใหม่).
     */
    public function store(Request $request)
    {
        $request->validate([
            'cus_id' => 'required|exists:cus_name,cus_id',
            'order_date' => 'required|date',
        ]);
    
        // เพิ่มข้อมูลในตาราง h_order และรับ order_id
        $orderId = DB::table('h_order')->insertGetId([
            'cus_id'     => $request->cus_id,
            'order_date' => $request->order_date,
        ], 'order_id'); // ระบุว่าคอลัมน์ที่ต้องการดึงกลับคือ order_id
    
        // Redirect ไปยังหน้าเพิ่มรายละเอียดออเดอร์
        return redirect()->route('orderDetails.create', ['orderId' => $orderId])
            ->with('success', 'เพิ่มคำสั่งซื้อเรียบร้อย! กรุณาเพิ่มรายการสินค้า');
    }

    /**
     * Display the specified resource (แสดงรายละเอียดคำสั่งซื้อ).
     */
    public function show($id)
    {
        $order = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->leftJoin('d_order', 'h_order.order_id', '=', 'd_order.order_id')
            ->select(
                'h_order.order_id',
                'h_order.cus_id',
                'cus_name.cus_name',
                'h_order.order_date',
                DB::raw('COUNT(d_order.d_id) as count_item'),
                DB::raw('SUM(d_order.amount) as total_amount')
            )
            ->where('h_order.order_id', $id)
            ->groupBy('h_order.order_id', 'h_order.cus_id', 'cus_name.cus_name', 'h_order.order_date')
            ->first();

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource (แก้ไขข้อมูลคำสั่งซื้อ).
     */
    public function edit($id)
    {
        // ดึงข้อมูลคำสั่งซื้อหลักจาก h_order และ cus_name
        $order = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->select('h_order.*', 'cus_name.cus_name')
            ->where('h_order.order_id', $id)
            ->first();

        // ดึงข้อมูลสินค้าในคำสั่งซื้อจาก d_order และ goods_name
        $orderItems = DB::table('d_order')
            ->join('goods_name', 'd_order.good_id', '=', 'goods_name.goods_id') // ใช้ goods_id ตามข้อมูลที่ให้มา
            ->select(
                'd_order.*',
                'goods_name.goods_name as product_name',
                'goods_name.cost_unit as unit_price'
            )
            ->where('d_order.order_id', $id)
            ->get();

        // ดึงรายการลูกค้าสำหรับ dropdown
        $customers = DB::table('cus_name')->get();

        return view('order-detail.index', compact('order', 'orderItems', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cus_id' => 'required',
            'order_date' => 'required|date',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        // อัปเดตข้อมูลคำสั่งซื้อหลัก (h_order)
        DB::table('h_order')
            ->where('order_id', $id)
            ->update([
                'cus_id'     => $request->cus_id,
                'order_date' => $request->order_date,
            ]);

        // อัปเดตข้อมูลรายการสินค้า (d_order)
        foreach ($request->items as $item) {
            DB::table('d_order')
                ->where('d_id', $item['d_id'])
                ->update([
                    'amount'       => $item['quantity'],
                    'cost_unit'    => $item['unit_price'],
                    'tot_prc'      => $item['quantity'] * $item['unit_price'],
                ]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'แก้ไขข้อมูลคำสั่งซื้อเรียบร้อย!');
    }


    /**
     * Remove the specified resource from storage (ลบคำสั่งซื้อ).
     */
    public function destroy($id)
    {
        DB::table('h_order')->where('order_id', $id)->delete();

        return redirect()->route('orders.index')
            ->with('success', 'ลบข้อมูลคำสั่งซื้อเรียบร้อย!');
    }
}
