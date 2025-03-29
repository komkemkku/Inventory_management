<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/goods-edit.css') }}">
</head>

<body>
    <div class="container">
        <h1>แก้ไขข้อมูลสินค้า</h1>

        @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('goods.update', $good->goods_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="goods_id">รหัสสินค้า:</label>
                <input type="text" name="goods_id" id="goods_id" value="{{ $good->goods_id }}" disabled>
            </div>

            <div class="form-group">
                <label for="goods_name">ชื่อสินค้า:</label>
                <input type="text" name="goods_name" id="goods_name" value="{{ $good->goods_name }}" required>
            </div>

            <div class="form-group">
                <label for="cost_unit">ราคา/หน่วย:</label>
                <input type="number" step="0.01" name="cost_unit" id="cost_unit" value="{{ $good->cost_unit }}" required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-save">บันทึก</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('goods.index') }}';">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
</body>

</html>