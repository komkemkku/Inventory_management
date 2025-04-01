<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>แสดงข้อมูลการสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>

<body>

    <div class="header-row">
        <h1 class="header-title">ตารางข้อมูลการสั่งซื้อสินค้า</h1>
        <div class="header-buttons">
        <a href="{{ route('home.index') }}">หน้าเมนู</a>
            <!-- <a href="{{ route('goods.index') }}">สินค้า</a>
            <a href="{{ route('customers.index') }}">ลูกค้า</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">ออกจากระบบ</button>
            </form> -->
        </div>
    </div>

    @if(session('success'))
    <div class="success-msg">
        {{ session('success') }}
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>รหัสลูกค้า</th>
                <th>ชื่อลูกค้า</th>
                <th>หมายเลขคำสั่งซื้อ</th>
                <th>จำนวนรายการที่สั่ง</th>
                <th>จำนวนที่สั่ง</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($orders) > 0)
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->cus_id }}</td>
                <td>{{ $order->cus_name }}</td>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->count_item }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>
                    <!-- ปุ่มแก้ไข -->
                    <a href="{{ route('orderDetails.index', $order->order_id) }}" class="action-btn btn-edit">แก้ไข</a>

                    <!-- ปุ่มลบ -->
                    <form action="{{ route('orders.destroy', $order->order_id) }}" method="DELETE" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="confirmDelete(event)">ลบ</button>
                    </form>

                </td>
            </tr>
            @endforeach
            @else

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
    </a><br>
    <!-- <a href="{{ route('reports.index') }}" class="btn-show">
        รายงานกำหนดการสั่งซื้อสินค้า
    </a><br>
    <a href="{{ route('processings.index') }}" class="btn-proces">
        การประมวลผลข้อมูลสั่งซื้อสินค้า
    </a><br> -->

</body>
<!-- โหลด SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(e) {
        e.preventDefault();
        const form = e.target.form;

        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบคำสั่งซื้อนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

</html>