<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>รายละเอียดคำสั่งซื้อ</title>
    <link rel="stylesheet" href="{{ asset('css/detail-order.css') }}">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>รายละเอียดคำสั่งซื้อ</h1>
            <p>สถานะ : แสดงข้อมูลคำสั่งซื้อ</p>
        </div>

        <!-- Customer Information -->
        <h4>รายละเอียดลูกค้า</h4>
        <div class="section-line">
            <label for="customer-code">รหัสลูกค้า :</label>
            <span id="customer-code">{{ $order->customer_code }}</span>
            <label for="customer-name">ชื่อลูกค้า :</label>
            <span id="customer-name">{{ $order->customer_name }}</span>
            <label for="order-date">วันที่สั่ง :</label>
            <span id="order-date">{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</span>
            <label for="order-number">Order No :</label>
            <span id="order-number">{{ $order->order_number }}</span>
        </div>

        <!-- Product Information Table -->
        <div class="section">
            <h2>รายละเอียดสินค้า</h2>
            <table>
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>วันที่กำหนดส่งสินค้า</th>
                        <th>วันที่ส่งสินค้า</th>
                        <th>จำนวนที่สั่ง</th>
                        <th>ราคาต่อหน่วย</th>
                        <th>ราคารวม</th>
                        <th>สถานะ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orderItems->isNotEmpty())
                    @foreach($orderItems as $item)
                    <tr>
                        <td>{{ $item->product_code }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->order_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->final_date)->format('Y-m-d') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->total_price }}</td>
                        <!-- <td>{{ $item->status }}</td> -->
                        <td>
                            @if($item->status === 'pending')
                            <span style="color: orange;">กำลังรอดำเนินการ</span>
                            @elseif($item->status === 'shipped')
                            <span style="color: green;">จัดส่งแล้ว</span>
                            @else
                            <span style="color: gray;">ไม่ทราบสถานะ</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('orderDetails.edit', $item->d_id) }}" class="action-btn btn-edit">แก้ไข</a>
                            <form action="{{ route('orderDetails.destroy', $item->d_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="confirmDelete(event)">ลบ</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" style="text-align: center; color: red;">ไม่พบข้อมูลสินค้า</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <button type="button" onclick="window.location.href='{{ route('orders.index') }}';" class="btn-back">กลับหน้ารายการ</button>
        </div>
    </div>
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