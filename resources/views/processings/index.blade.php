<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>ประมวลผลข้อมูล</title>
    <link rel="stylesheet" href="{{ asset('css/processing.css') }}">
</head>

<body>
    <div class="container">
        <h1>การประมวลผลข้อมูลสั่งซื้อสินค้า</h1>

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

        <form action="{{ route('processings.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="start_date">วันที่เริ่มต้น :</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">วันที่สิ้นสุด :</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>

            <div class="buttons">
                <button type="submit" class="btn-submit">ตกลง</button>
                <a href="{{ route('home.index') }}" class="btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>

</html>