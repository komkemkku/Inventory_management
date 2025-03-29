<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>รายการลูกค้า</title>
    <link rel="stylesheet" href="{{ asset('css/customers-index.css') }}">
</head>

<body>
    <!-- ส่วนหัว: ชื่อและปุ่มเพิ่มลูกค้า -->
    <div class="header-row">
        <h1 class="header-title">ตารางข้อมูลลูกค้า</h1>
        <div class="header-buttons">
            <a href="{{ route('customers.create') }}" class="btn-add">เพิ่มลูกค้า</a>
            <a href="{{ route('orders.index') }}" class="btn-back">กลับหน้าหลัก</a>
        </div>
    </div>

    <!-- แสดงข้อความ success ถ้ามี -->
    @if(session('success'))
    <div class="success-msg">{{ session('success') }}</div>
    @endif

    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <table>
        <thead>
            <tr>
                <th>รหัสลูกค้า</th>
                <th>ชื่อลูกค้า</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($customers) > 0)
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->cus_id }}</td>
                <td>{{ $customer->cus_name }}</td>
                <td>
                    <a href="{{ route('customers.update', $customer->cus_id) }}" class="action-btn btn-edit">แก้ไข</a>
                    <form action="{{ route('customers.destroy', $customer->cus_id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="3" class="no-data">ยังไม่มีข้อมูลลูกค้า</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>

</html>