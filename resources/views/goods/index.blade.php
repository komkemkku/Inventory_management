<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>รายการสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/goods-index.css') }}">
</head>

<body>

    <div class="header-row">
        <h1 class="header-title">ตารางข้อมูลสินค้า</h1>
        <div class="header-buttons">
            <!-- ปุ่มเพิ่มสินค้า -->
            <a href="{{ route('goods.create') }}" class="btn-add">เพิ่มสินค้า</a>
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

    <!-- ตารางแสดงรายการสินค้า -->
    <table>
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคา/หน่วย</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($goods) > 0)
            @foreach($goods as $g)
            <tr>
                <td>{{ $g->goods_id }}</td>
                <td>{{ $g->goods_name }}</td>
                <td>{{ $g->cost_unit }}</td>
                <td>
                    <a href="{{ route('goods.edit', $g->goods_id) }}" class="action-btn btn-edit">แก้ไข</a>

                    <form action="{{ route('goods.destroy', $g->goods_id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete"
                            onclick="confirmDelete(event)">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach

            @else
            <tr>
                <td colspan="4" class="no-data">ยังไม่มีข้อมูลสินค้า</td>
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
            text: "คุณต้องการลบสินค้านี้หรือไม่?",
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