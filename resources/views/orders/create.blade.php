<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>การบันทึกใบการสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/orders-create.css') }}">
</head>

<body>

    <div class="form-container">


        <div class="form-header">
            <span>การบันทึก/แก้ไขใบการสั่งซื้อสินค้า</span>
        </div>


        <h2 class="form-subtitle">สถานะ : เพิ่มรายการส่วน Header ให้ตรงกับข้อมูลการสั่งซื้อ</h2>

        <!-- แสดง Error ถ้ามี -->
        @if ($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <table class="table-form">
                <!-- แถวสำหรับเลือกชื่อลูกค้า -->
                <tr>
                    <td class="label-cell">รหัสลูกค้า :</td>
                    <td class="input-cell">
                        <select name="cus_id" id="cus_id" onchange="showCustomerName()">
                            <option value="" data-name="">-- เลือกลูกค้า --</option>
                            @foreach($customers as $cus)
                            <option value="{{ $cus->cus_id }}" data-name="{{ $cus->cus_name }}">
                                {{ $cus->cus_id }}
                            </option>
                            @endforeach
                        </select>


                        <span id="customer_name_display" style="margin-left: 1rem; color: #000;">
                            ชื่อลูกค้า :
                        </span>

                        <!-- <p class="add-customer">
                            ไม่พบลูกค้าที่ต้องการ?
                            <a href="{{ route('customers.create') }}">เพิ่มลูกค้าใหม่</a>
                        </p> -->
                    </td>
                </tr>

                <!-- แถวสำหรับกรอกวันที่สั่ง -->
                <tr>
                    <td class="label-cell">วันที่สั่งซื้อ :</td>
                    <td class="input-cell">
                        <input type="date" name="order_date" placeholder="DD/MM/YYYY" value="{{ date('Y-m-d') }}" />
                        <small style="color: #888;">(Default เป็นวันที่ปัจจุบัน)</small>
                    </td>
                </tr>
            </table>

            <!-- ปุ่มบันทึก / ยกเลิก -->
            <div class="button-group">
                <button type="submit" class="btn btn-save">บันทึกและเพิ่มรายการสินค้า</button>
                <button
                    type="button"
                    class="btn btn-cancel"
                    onclick="window.location.href='{{ route('orders.index') }}';">
                    ยกเลิก
                </button>
            </div>
        </form>

    </div>

</body>
<script>
    window.onload = function() {
        showCustomerName();
    };

    function showCustomerName() {
        const sel = document.getElementById('cus_id');
        const selectedOpt = sel.options[sel.selectedIndex];
        const cusName = selectedOpt.getAttribute('data-name') || '';

        const displaySpan = document.getElementById('customer_name_display');
        displaySpan.textContent = `ชื่อลูกค้า : ${cusName}`;
    }
</script>

</html>