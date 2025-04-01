<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>การบันทึก/แก้ไขคำสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/order-detail-create.css') }}">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>การบันทึก/แก้ไข การสั่งซื้อสินค้า</h1>
            <p>สถานะ: เพิ่มรายการสินค้า</p>
        </div>

        <!-- Customer Details Section -->
        <h2>รายละเอียดลูกค้า</h2>
        <div class="section">
            <div class="form-group-cus">
                <div class="form-field">
                    <label>รหัสลูกค้า :</label>
                    <input type="text" readonly value="{{ $order->customer_code }}">
                </div>
                <div class="form-field">
                    <label>ชื่อลูกค้า :</label>
                    <input type="text" readonly value="{{ $order->customer_name }}">
                </div>
                <div class="form-field">
                    <label>วันที่สั่ง :</label>
                    <input type="date" readonly value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}">
                </div>
                <div class="form-field">
                    <label>Order No :</label>
                    <input type="text" readonly value="{{ $order->order_number }}">
                </div>
            </div>
        </div>

        <hr>

        <!-- Form for Adding Product -->
        <h2>เพิ่มรายละเอียดสินค้า</h2>
        <form action="{{ route('orderDetails.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->order_number }}">

            <div class="form-group-product">
                <label for="good_id">เลือกสินค้า :</label>
                <select name="good_id" id="good_id" required onchange="updateCostUnit()">
                    <option value="" data-name="">-- เลือกสินค้า --</option>
                    @foreach($goods as $good)
                    <option value="{{ $good->goods_id }}" data-cost="{{ $good->cost_unit }}">{{ $good->goods_name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group-date">
                <label for="ord_date">วันที่ส่งสินค้า :</label>
                <input type="date" id="ord_date" name="ord_date" required>

                <label for="fin_date">วันที่กำหนดส่ง :</label>
                <input type="date" id="fin_date" name="fin_date" required>
            </div>

            <div class="form-group-price">
                <label for="amount">จำนวน :</label>
                <input type="number" id="amount" name="amount" min="1" required onchange="updateTotalPrice()">

                <label for="cost_unit">ราคาต่อหน่วย :</label>
                <input type="number" id="cost_unit" name="cost_unit" step="0.01" readonly>

                <label for="total_price">ราคารวม :</label>
                <input type="number" id="total_price" name="total_price" readonly>
            </div>


            <button type="submit" class="btn-submit">เพิ่มสินค้า</button>
        </form>

        <hr>

        <!-- Product Table Section -->
        <h2>รายการสินค้าในคำสั่งซื้อ</h2>
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>รายละเอียด</th>
                        <th>วันที่กำหนดส่ง</th>
                        <th>วันที่ส่งสินค้า</th>
                        <th>จำนวน</th>
                        <th>ราคาต่อหน่วย</th>
                        <th>ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderItems as $item)
                    <tr>
                        <td>{{ $item->product_code }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->order_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->delivery_date)->format('Y-m-d') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->total_price }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">ไม่มีข้อมูลสินค้า</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Footer -->
        <div class="footer">
            <button type="button" onclick="window.location.href='{{ route('orders.index') }}';" class="btn-back">กลับหน้ารายการ</button>
        </div>
    </div>
</body>
<script>
    function updateCostUnit() {
        const selectedProduct = document.querySelector('#good_id');
        const costUnit = selectedProduct.options[selectedProduct.selectedIndex].dataset.cost || 0;

        document.getElementById('cost_unit').value = parseFloat(costUnit).toFixed(2);

        updateTotalPrice();
    }

    function updateTotalPrice() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const costUnit = parseFloat(document.getElementById('cost_unit').value) || 0;

        const totalPrice = amount * costUnit;

        document.getElementById('total_price').value = totalPrice.toFixed(2);
    }
</script>

</html>