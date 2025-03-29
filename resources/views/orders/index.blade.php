<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>แสดงข้อมูลการสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>

<body>

    <div class="header-row">
        <h1 class="header-title">ตารางข้อมูลการสั่งซื้อสินค้า</h1>
        <div class="header-buttons">
            <a href="{{ route('goods.index') }}">สินค้า</a>
            <a href="{{ route('customers.index') }}">ลูกค้า</a>
        </div>
    </div>

    <!-- ตรวจสอบว่ามี session 'success' หรือไม่ -->
    @if(session('success'))
    <div style="color: green; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>รหัสลูกค้า (CUS_ID)</th>
                <th>ชื่อลูกค้า (CUS_NAME)</th>
                <th>หมายเลขคำสั่งซื้อ (H_ORDER_NO)</th>
                <th>จำนวนรายการที่สั่ง</th>
                <th>จำนวนที่สั่ง</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- เช็กก่อนว่ามีข้อมูลหรือไม่ -->
            @if(count($orders) > 0)
            @foreach($orders as $order)
            <tr>
                <!-- ตอนนี้ $order->cus_id มีค่าแล้ว -->
                <td>{{ $order->cus_id }}</td>
                <td>{{ $order->cus_name }}</td>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->count_item }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>
                    <button class="action-btn btn-edit">แก้ไข</button>
                    <button class="action-btn btn-delete">ลบ</button>
                </td>
            </tr>
            @endforeach
            @else
            <!-- ถ้าไม่มีข้อมูล ให้แสดงข้อความ -->
            <tr>
                <td colspan="6" style="text-align: center; color: red;">
                    ยังไม่มีข้อมูล
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <a href="{{ route('orders.create') }}" class="btn-add">
        เพิ่มข้อมูลการสั่งซื้อสินค้า
    </a>

</body>

</html>