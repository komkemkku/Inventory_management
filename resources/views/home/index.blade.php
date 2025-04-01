<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="container">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">ออกจากระบบ</button>
        </form>
        <h1>ระบบโปรแกรมบริหารจัดการสินค้าคงคลัง</h1>
        <table class="main-table">
            <thead>
                <tr>
                    <th>ข้อมูลอ้างอิง</th>
                    <th>การทำงานประจำวัน</th>
                    <th>รายงาน</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <button onclick="location.href='{{ route('customers.index') }}'">ข้อมูลลูกค้า</button>
                        <button onclick="location.href='{{ route('goods.index') }}'">ข้อมูลสินค้า</button>
                    </td>
                    <td>
                        <button onclick="location.href='{{ route('orders.index') }}'">การสั่งซื้อ</button>
                        <button onclick="location.href='{{ route('processings.index') }}'">ประมวลผลสินค้า</button>
                    </td>
                    <td>
                        <button onclick="location.href='{{ route('reports.index') }}'">รายงานกำหนดส่ง</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>