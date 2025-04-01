<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * แสดงรายการลูกค้า (customers.index)
     */
    public function index()
    {

        $customers = DB::table('cus_name')
            ->orderBy('cus_id', 'asc')
            ->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * แสดงฟอร์มเพิ่มลูกค้า (customers.create)
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * บันทึกข้อมูลลูกค้าใหม่ลงตาราง cus_name
     */
    public function store(Request $request)
    {
        // ตรวจสอบและ validate ข้อมูลชื่อ
        $request->validate([
            'cus_name' => 'required|string|max:255',
        ]);

        $lastCustomerId = DB::table('cus_name')->max('cus_id');
        $numericId = $lastCustomerId ? intval(str_replace('C-', '', $lastCustomerId)) + 1 : 1;
        $newCustomerId = 'C-' . str_pad($numericId, 5, '0', STR_PAD_LEFT);

        // เพิ่มข้อมูลลูกค้าใหม่ลงในฐานข้อมูล
        DB::table('cus_name')->insert([
            'cus_id'   => $newCustomerId,
            'cus_name' => $request->cus_name,
        ]);

        // ส่งกลับไปยังหน้าแสดงรายการลูกค้า พร้อมข้อความสำเร็จ
        return redirect()->route('customers.index')
            ->with('success', 'เพิ่มลูกค้าเรียบร้อย!');
    }

    /**
     * แสดงฟอร์มแก้ไขข้อมูลลูกค้า (customers.edit)
     */
    public function edit($id)
    {

        $customer = DB::table('cus_name')->where('cus_id', $id)->first();
        if (!$customer) {
            abort(404, 'ไม่พบลูกค้า');
        }
        return view('customers.edit', compact('customer'));
    }

    /**
     * อัปเดตข้อมูลลูกค้าในฐานข้อมูล (customers.update)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cus_name' => 'required|string|max:255',
        ]);

        DB::table('cus_name')
            ->where('cus_id', $id)
            ->update([
                'cus_name' => $request->cus_name,
            ]);

        return redirect()->route('customers.index')
            ->with('success', 'แก้ไขข้อมูลลูกค้าเรียบร้อย!');
    }

    /**
     * ลบลูกค้าออกจากฐานข้อมูล (customers.destroy)
     */
    public function destroy($id)
    {

        DB::table('cus_name')->where('cus_id', $id)->delete();

        return redirect()->route('customers.index')
            ->with('success', 'ลบลูกค้าเรียบร้อย!');
    }
}
