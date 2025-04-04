<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    /**
     * แสดงรายการสินค้าทั้งหมดในคำสั่งซื้อ
     */
    public function index($orderId)
    {
        // ดึงข้อมูลคำสั่งซื้อและลูกค้า
        $order = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->select(
                'cus_name.cus_id as customer_code',
                'cus_name.cus_name as customer_name',
                'h_order.order_date as order_date',
                'h_order.order_id as order_number'
            )
            ->where('h_order.order_id', $orderId)
            ->first();

        // ตรวจสอบว่ามีคำสั่งซื้อหรือไม่
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'ไม่พบคำสั่งซื้อที่ระบุ');
        }

        // ดึงข้อมูลสินค้าในคำสั่งซื้อ
        $orderItems = DB::table('d_order')
            ->join('goods_name', 'd_order.good_id', '=', 'goods_name.goods_id')
            ->select(
                'd_order.d_id as d_id',
                'goods_name.goods_id as product_code',
                'goods_name.goods_name as product_name',
                'd_order.ord_date as order_date',
                'd_order.fin_date as final_date',
                'd_order.amount as quantity',
                'd_order.status as status',
                'goods_name.cost_unit as unit_price',
                DB::raw('d_order.amount * goods_name.cost_unit as total_price')
            )
            ->where('d_order.order_id', $orderId)
            ->get();

        $goods = DB::table('goods_name')->get();

        return view('order-details.index', compact('order', 'orderItems', 'goods'));
    }

    /**
     * แสดงฟอร์มสำหรับเพิ่มสินค้าในคำสั่งซื้อ
     */
    public function create($orderId)
    {
        // ดึงข้อมูลคำสั่งซื้อและลูกค้า
        $order = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->select(
                'cus_name.cus_id as customer_code',
                'cus_name.cus_name as customer_name',
                'h_order.order_date as order_date',
                'h_order.order_id as order_number'
            )
            ->where('h_order.order_id', $orderId)
            ->first();

        // ตรวจสอบว่าพบคำสั่งซื้อหรือไม่
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'ไม่พบคำสั่งซื้อที่ระบุ');
        }

        // ดึงข้อมูลสินค้าในคำสั่งซื้อ
        $orderItems = DB::table('d_order')
            ->join('goods_name', 'd_order.good_id', '=', 'goods_name.goods_id')
            ->select(
                'goods_name.goods_id as product_code',
                'goods_name.goods_name as product_name',
                'd_order.ord_date as order_date',
                'd_order.fin_date as delivery_date',
                'd_order.amount as quantity',
                'goods_name.cost_unit as unit_price',
                DB::raw('d_order.amount * goods_name.cost_unit as total_price')
            )
            ->where('d_order.order_id', $orderId)
            ->get();


        $goods = DB::table('goods_name')->get();


        return view('order-details.create', compact('order', 'orderItems', 'goods'));
    }

    /**
     * บันทึกสินค้าที่เพิ่มในคำสั่งซื้อ
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:h_order,order_id',
            'good_id' => 'required|exists:goods_name,goods_id',
            'amount' => 'required|integer|min:1',
            'ord_date' => 'required|date',
            'fin_date' => 'required|date',
            'cost_unit' => 'required|numeric|min:0',
        ]);

        // ตรวจสอบว่ามีสินค้านี้ในคำสั่งซื้อหรือไม่
        $existingItem = DB::table('d_order')
            ->where('order_id', $request->order_id)
            ->where('good_id', $request->good_id)
            ->first();

        if ($existingItem) {
            // หากมีสินค้าอยู่แล้ว ทำการอัปเดตจำนวนสินค้าและราคารวม
            DB::table('d_order')
                ->where('order_id', $request->order_id)
                ->where('good_id', $request->good_id)
                ->update([
                    'amount' => $existingItem->amount + $request->amount,
                    'tot_prc' => ($existingItem->amount + $request->amount) * $request->cost_unit, // อัปเดตราคารวม
                    'ord_date' => $request->ord_date,
                    'fin_date' => $request->fin_date,
                ]);

            return redirect()->route('orderDetails.create', $request->order_id)
                ->with('success', 'อัปเดตข้อมูลสินค้าในคำสั่งซื้อเรียบร้อย!');
        } else {
            // หากไม่มีสินค้า ทำการเพิ่มรายการใหม่
            DB::table('d_order')->insert([
                'order_id'   => $request->order_id,
                'good_id'    => $request->good_id,
                'ord_date'   => $request->ord_date,
                'fin_date'   => $request->fin_date,
                'amount'     => $request->amount,
                'cost_unit'  => $request->cost_unit,
                'tot_prc'    => $request->amount * $request->cost_unit,
                'status' => 'pending',
            ]);

            return redirect()->route('orderDetails.create', $request->order_id)
                ->with('success', 'เพิ่มข้อมูลสินค้าในคำสั่งซื้อเรียบร้อย!');
        }
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขสินค้าในคำสั่งซื้อ
     */
    public function edit($id)
    {
        // ดึงข้อมูลสินค้าที่จะแก้ไข
        $orderDetail = DB::table('d_order')
            ->join('goods_name', 'd_order.good_id', '=', 'goods_name.goods_id')
            ->select(
                'd_order.d_id as d_id',
                'd_order.order_id as order_id',
                'goods_name.goods_id as product_code',
                'goods_name.goods_name as product_name',
                'd_order.ord_date as ord_date',
                'd_order.fin_date as fin_date',
                'd_order.amount as quantity',
                'goods_name.cost_unit as unit_price',
                'd_order.tot_prc as total_price',
                'd_order.status as status'

            )
            ->where('d_order.d_id', $id)
            ->first();

        if (!$orderDetail) {
            return redirect()->route('orders.index')->with('error', 'ไม่พบรายการสินค้าที่ระบุ');
        }

        $statuses = ['pending', 'shipped'];


        // ดึงข้อมูลลูกค้าและคำสั่งซื้อที่เกี่ยวข้อง
        $order = DB::table('h_order')
            ->join('cus_name', 'h_order.cus_id', '=', 'cus_name.cus_id')
            ->select(
                'cus_name.cus_id as customer_code',
                'cus_name.cus_name as customer_name',
                'h_order.order_date as order_date',
                'h_order.order_id as order_number'
            )
            ->where('h_order.order_id', $orderDetail->order_id)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'ไม่พบข้อมูลคำสั่งซื้อที่ระบุ');
        }

        // ดึงรายการสินค้าทั้งหมดเพื่อให้เลือกได้
        $goods = DB::table('goods_name')->get();

        return view('order-details.edit', compact('orderDetail', 'order', 'goods', 'statuses'));
    }

    /**
     * อัปเดตข้อมูลสินค้าในคำสั่งซื้อ
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'good_id' => 'required|exists:goods_name,goods_id',
            'amount' => 'nullable|integer|min:1',
            'ord_date' => 'nullable|date',
            'fin_date' => 'nullable|date',
            'status' => 'required|in:pending,shipped',

        ]);

        // ดึงราคาต่อหน่วยจากตารางสินค้า
        $unitPrice = DB::table('goods_name')
            ->where('goods_id', $request->good_id)
            ->value('cost_unit');

        if (!$unitPrice) {
            return redirect()->route('orderDetails.edit', $id)->with('error', 'ไม่พบราคาสินค้าที่เลือก');
        }

        // เตรียมข้อมูลที่จะอัปเดต
        $dataToUpdate = [
            'good_id' => $request->good_id,
            'cost_unit' => $unitPrice,
            'status' => $request->status,

        ];

        // ตรวจสอบและอัปเดตจำนวนสินค้า
        if ($request->amount) {
            $dataToUpdate['amount'] = $request->amount;
            $dataToUpdate['tot_prc'] = $request->amount * $unitPrice;
        }

        // ตรวจสอบและอัปเดตวันที่
        if ($request->ord_date) {
            $dataToUpdate['ord_date'] = $request->ord_date;
        }
        if ($request->fin_date) {
            $dataToUpdate['fin_date'] = $request->fin_date;
        }

        // อัปเดตข้อมูลสินค้าในฐานข้อมูล
        DB::table('d_order')
            ->where('d_id', $id)
            ->update($dataToUpdate);

        return redirect()->route('orderDetails.index', $request->order_id)
            ->with('success', 'แก้ไขข้อมูลสินค้าเรียบร้อย!');
    }

    /**
     * ลบสินค้าจากคำสั่งซื้อ
     */
    public function destroy($id)
    {
        // ตรวจสอบสินค้าที่จะลบ
        $orderDetail = DB::table('d_order')->where('d_id', $id)->first();

        if (!$orderDetail) {
            return back()->with('error', 'ไม่พบรายการสินค้าที่ระบุ');
        }

        $orderId = $orderDetail->order_id;

        $affectedRows = DB::table('d_order')->where('d_id', $id)->delete();

        if ($affectedRows === 0) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูลสินค้า!');
        }

        $remainingItems = DB::table('d_order')->where('order_id', $orderId)->count();

        if ($remainingItems === 0) {
            // ลบคำสั่งซื้อออกจาก h_order หากไม่มีสินค้าเหลืออยู่
            DB::table('h_order')->where('order_id', $orderId)->delete();
        }

        return back()->with('success', 'ลบข้อมูลสินค้าสำเร็จและตรวจสอบคำสั่งซื้อเรียบร้อยแล้ว!');
    }
}
