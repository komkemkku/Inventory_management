<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลลูกค้า</title>
    <link rel="stylesheet" href="{{ asset('css/customers-edit.css') }}">
</head>

<body>
    <div class="container">
        <h1>แก้ไขข้อมูลลูกค้า</h1>

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

        <form action="{{ route('customers.update', $customer->cus_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="cus_id">รหัสลูกค้า:</label>
                <input type="text" name="cus_id" id="cus_id" value="{{ $customer->cus_id }}" disabled>
            </div>

            <div class="form-group">
                <label for="cus_name">ชื่อลูกค้า:</label>
                <input type="text" name="cus_name" id="cus_name" value="{{ $customer->cus_name }}" required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-save">บันทึก</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('customers.index') }}';">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
</body>

</html>