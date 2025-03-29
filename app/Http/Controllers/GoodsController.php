<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    /**
     * แสดงรายการสินค้า (index).
     */
    public function index()
    {

        $goods = DB::table('goods_name')
            ->orderBy('goods_id', 'asc')
            ->get();

        return view('goods.index', compact('goods'));
    }

    /**
     * แสดงฟอร์มสร้างสินค้าใหม่ (create).
     */
    public function create()
    {
        return view('goods.create');
    }

    /**
     * บันทึกสินค้าใหม่ลง DB (store).
     */
    public function store(Request $request)
    {

        $request->validate([
            'goods_name' => 'required|string|max:100',
            'cost_unit'  => 'required|numeric'
        ]);


        DB::table('goods_name')->insert([
            // ไม่รับ goods_id จากผู้ใช้
            'goods_name' => $request->goods_name,
            'cost_unit'  => $request->cost_unit
        ]);

        return redirect()->route('goods.index')
            ->with('success', 'เพิ่มสินค้าเรียบร้อย!');
    }

    /**
     * แสดงรายละเอียดสินค้า (show).
     */
    public function show(string $id)
    {
        $good = DB::table('goods_name')->where('goods_id', $id)->first();
        if (!$good) {
            abort(404, 'ไม่พบสินค้า');
        }

        return view('goods.show', compact('good'));
    }

    /**
     * แสดงฟอร์มแก้ไขสินค้า (edit).
     */
    public function edit(string $id)
    {

        $good = DB::table('goods_name')->where('goods_id', $id)->first();
        if (!$good) {
            abort(404, 'ไม่พบสินค้า');
        }

        return view('goods.edit', compact('good'));
    }

    /**
     * อัปเดตสินค้าใน DB (update).
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'goods_name' => 'required|string|max:100',
            'cost_unit'  => 'required|numeric'
        ]);

        // ตรวจสอบว่าสินค้าที่จะแก้ไขมีอยู่จริงหรือไม่
        $good = DB::table('goods_name')->where('goods_id', $id)->first();
        if (!$good) {
            abort(404, 'ไม่พบสินค้า');
        }

        // Update ข้อมูลในตาราง
        DB::table('goods_name')->where('goods_id', $id)->update([
            'goods_name' => $request->goods_name,
            'cost_unit'  => $request->cost_unit
        ]);

        return redirect()->route('goods.index')
            ->with('success', 'แก้ไขสินค้าเรียบร้อย!');
    }

    /**
     * ลบสินค้า (destroy).
     */
    public function destroy(string $id)
    {
        // ตรวจสอบว่าสินค้ามีอยู่หรือไม่
        $good = DB::table('goods_name')->where('goods_id', $id)->first();
        if (!$good) {
            abort(404, 'ไม่พบสินค้า');
        }

        // ลบสินค้า
        DB::table('goods_name')->where('goods_id', $id)->delete();

        return redirect()->route('goods.index')
            ->with('success', 'ลบสินค้าเรียบร้อย!');
    }
}
