<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>รายการลูกค้า</title>
    <link rel="stylesheet" href="{{ asset('css/customers-index.css') }}">
</head>

<body>
    <!-- ส่วนหัว: ชื่อและปุ่มเพิ่มลูกค้า -->
    <div class="header-row">
        <h1 class="header-title">ตารางข้อมูลลูกค้า</h1>
        <div class="header-buttons">
            <a href="{{ route('customers.create') }}" class="btn-add">เพิ่มลูกค้า</a>
            <a href="{{ route('home.index') }}" class="btn-back">หน้าเมนู</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
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
                    <a href="{{ route('customers.edit', $customer->cus_id) }}" class="action-btn btn-edit">แก้ไข</a>
                    <form action="{{ route('customers.destroy', $customer->cus_id) }}" method="DELETE" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" onclick="confirmDelete(event)">ลบ</button>
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
<!-- โหลด SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(e) {
        e.preventDefault();
        const form = e.target.form;

        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบลูกค้านี้หรือไม่?",
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