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
            'cus_id' => 'required',
            'order_date' => 'required|date',

        ]);

        // บันทึกลงตาราง h_order
        DB::table('h_order')->insert([
            'cus_id'     => $request->cus_id,
            'order_date' => $request->order_date,
            // order_id เป็น auto increment (serial) 
        ]);


        return redirect()->route('orders.index')
            ->with('success', 'เพิ่มข้อมูลคำสั่งซื้อเรียบร้อย!');
    }

    /**
     * Display the specified resource (แสดงรายละเอียดคำสั่งซื้อ).
     */
    public function show($id)
    {
        return 'Show detail for order ' . $id;
    }

    /**
     * Show the form for editing the specified resource (แก้ไขข้อมูลคำสั่งซื้อ).
     */
    public function edit($id)
    {
        return 'Edit form for order ' . $id;
    }

    /**
     * Update the specified resource in storage (อัปเดตข้อมูลคำสั่งซื้อ).
     */
    public function update(Request $request, $id)
    {
        return 'Update order ' . $id;
    }

    /**
     * Remove the specified resource from storage (ลบคำสั่งซื้อ).
     */
    public function destroy($id)
    {
        return 'Delete order ' . $id;
    }
}
