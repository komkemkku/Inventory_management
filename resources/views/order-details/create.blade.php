<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <input type="date" readonly value="{{ $order->order_date }}">
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
                <select name="good_id" id="good_id" required>
                    @foreach($goods as $good)
                    <option value="{{ $good->goods_id }}">{{ $good->goods_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group-date">
                <label for="ord_date">วันที่สั่ง :</label>
                <input type="date" id="ord_date" name="ord_date" required>

                <label for="fin_date">วันที่ส่ง :</label>
                <input type="date" id="fin_date" name="fin_date" required>
            </div>

            <div class="form-group-price">
                <label for="amount">จำนวน :</label>
                <input type="number" id="amount" name="amount" min="1" required>

                <label for="cost_unit">ราคาต่อหน่วย :</label>
                <input type="number" id="cost_unit" name="cost_unit" step="0.01" required>

                <label for="cost_unit">ราคารวม :</label>
                <input type="number" id="cost_unit" name="cost_unit" step="0.01" required>
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
                        <th>วันที่ส่ง</th>
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
                        <td>{{ $item->order_date }}</td>
                        <td>{{ $item->delivery_date }}</td>
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
    </div>
</body>

</html>