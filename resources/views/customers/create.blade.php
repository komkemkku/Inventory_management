<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มลูกค้าใหม่</title>
    <!-- อ้างอิงไฟล์ CSS ที่อยู่ใน public/css/customers.css -->
    <link rel="stylesheet" href="{{ asset('css/customers-create.css') }}">
</head>

<body>
    <div class="container">
        <h1>เพิ่มลูกค้าใหม่</h1>

        @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="cus_id">รหัสลูกค้า:</label>
                <input type="text" name="cus_id" id="cus_id" required>
            </div>
            <div class="form-group">
                <label for="cus_name">ชื่อลูกค้า:</label>
                <input type="text" name="cus_name" id="cus_name" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-save">บันทึก</button>
                <button
                    type="button"
                    class="btn btn-cancel"
                    onclick="window.location.href='{{ route('customers.index') }}';">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
</body>

</html>