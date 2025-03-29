<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มสินค้าใหม่</title>
    <link rel="stylesheet" href="{{ asset('css/goods-create.css') }}">
</head>
<body>
    <div class="container">
        <h1>เพิ่มสินค้าใหม่</h1>

        <!-- แสดง Error ถ้ามี -->
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('goods.store') }}" method="POST">
            @csrf
            <!-- ไม่ต้องกรอกไอดีสินค้า เพราะจะสร้างอัตโนมัติ -->
            <div class="form-group">
                <label for="goods_name">ชื่อสินค้า:</label>
                <input type="text" name="goods_name" id="goods_name" required>
            </div>

            <div class="form-group">
                <label for="cost_unit">ราคา/หน่วย:</label>
                <input type="number" step="0.01" name="cost_unit" id="cost_unit" required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-save">บันทึก</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('goods.index') }}';">ยกเลิก</button>
            </div>
        </form>
    </div>
</body>
</html>
