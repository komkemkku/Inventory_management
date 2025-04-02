<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>การจัดการคำสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/order-detail-edit.css') }}">
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>การบันทึก/แก้ไข การสั่งซื้อสินค้า</h2>
            <p>สถานะ : แก้ไขรายการส่วน Detail การรับคำสั่งซื้อสินค้า</p>
        </div>
        <!-- Customer Section -->
        <div class="section-header">
            <h2>รายละเอียดลูกค้า</h2>
        </div>
        <div class="form-group-cus">
            <label>รหัสลูกค้า :</label>
            <input type="text" value="{{ $order->customer_code }}" readonly>
            <label>ชื่อลูกค้า :</label>
            <input type="text" value="{{ $order->customer_name }}" readonly>
            <label>วันที่สั่ง :</label>
            <input type="date" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" readonly>
            <label>Order No :</label>
            <input type="text" value="{{ $order->order_number }}" readonly>
        </div>

        <!-- Product Section -->
        <div class="section-header">
            <h2>ข้อมูลสินค้า</h2>
        </div>
        <form action="{{ route('orderDetails.update', $orderDetail->d_id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="order_id" value="{{ $order->order_number }}">

            <div class="status">
                <label>สถานะ : </label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ $orderDetail->status === 'pending' ? 'selected' : '' }}>กำลังรอดำเนินการ</option>
                    <option value="shipped" {{ $orderDetail->status === 'shipped' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                </select>
            </div>

            <!-- ดึงข้อมูลสินค้าสำหรับการแก้ไข -->
            <div class="form-group-product">
                <label>รหัสสินค้า :</label>
                <select id="product_code" name="good_id" onchange="updateProductDetails()" required>
                    <option value=""> >>---เลือกสินค้า---<< </option>
                            @foreach($goods as $good)
                    <option value="{{ $good->goods_id }}" data-name="{{ $good->goods_name }}" data-price="{{ $good->cost_unit }}"
                        {{ $good->goods_id == $orderDetail->product_code ? 'selected' : '' }}>
                        {{ $good->goods_id }}
                    </option>
                    @endforeach
                </select>
                <label>ชื่อสินค้า :</label>
                <input type="text" id="product_name" value="{{ $orderDetail->product_name }}" readonly>
            </div>

            <!-- ส่วนวันที่ -->
            <div class="form-group-date">
                <label>วันที่ส่งสินค้า :</label>
                <input type="date" id="actual_delivery_date" name="ord_date" value="{{ $orderDetail->ord_date }}">
                <label>วันที่กำหนดส่งสินค้า :</label>
                <input type="date" id="delivery_date" name="fin_date" value="{{ $orderDetail->fin_date }}">
            </div>

            <!-- ส่วนจำนวนและราคา -->
            <div class="form-group-amount">
                <label>จำนวนที่สั่ง :</label>
                <input type="number" id="quantity" name="amount" value="{{ $orderDetail->quantity }}" min="1" onchange="updateTotalPrice()">
                <label>ราคาต่อหน่วย :</label>
                <input type="number" id="cost_unit" name="cost_unit" value="{{ $orderDetail->unit_price }}" readonly>
                <label>ราคารวม :</label>
                <input type="number" id="total_price" name="tot_prc" value="{{ $orderDetail->total_price }}" readonly>
            </div>

            <!-- ปุ่มดำเนินการ -->
            <div class="action-buttons">
                <button type="submit" class="btn-save">บันทึกข้อมูล</button>
                <a href="{{ route('orderDetails.index', ['orderId' => $orderDetail->order_id]) }}" class="btn-back">กลับหน้าหลัก</a>
            </div>
        </form>
    </div>
</body>
<script>
    function updateProductDetails() {
        const selectedProduct = document.querySelector('#product_code');
        const productName = selectedProduct.options[selectedProduct.selectedIndex].dataset.name || '';
        const unitPrice = selectedProduct.options[selectedProduct.selectedIndex].dataset.price || 0;

        document.getElementById('product_name').value = productName;
        document.getElementById('cost_unit').value = parseFloat(unitPrice).toFixed(2);

        updateTotalPrice();
    }

    function updateTotalPrice() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const unitPrice = parseFloat(document.getElementById('cost_unit').value) || 0;

        document.getElementById('total_price').value = (quantity * unitPrice).toFixed(2);
    }
</script>

</html>