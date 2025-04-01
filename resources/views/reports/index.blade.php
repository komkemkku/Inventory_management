<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานกำหนดการสั่งซื้อสินค้า</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
</head>
<script>
    window.onbeforeprint = function() {
        document.title = '';
        document.querySelectorAll('header, footer').forEach(element => element.style.display = 'none');
    };

    window.onafterprint = function() {
        document.title = 'รายงานคำสั่งซื้อ';
        document.querySelectorAll('header, footer').forEach(element => element.style.display = '');
    };
</script>

<body>
    <div class="container">
        <!-- Header Section -->
        <h1>รายงานคำสั่งซื้อ</h1>
        <div class="filter-section">
            <form action="{{ route('reports.show') }}" method="GET">
                <label for="start_date">วันที่เริ่มต้น :</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" required>
                <label for="end_date">วันที่สิ้นสุด :</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" required>
                <div>
                    <button type="submit" class="btn-display">แสดง</button>
                </div>
            </form>
        </div>

        <!-- Report Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รายละเอียดลูกค้า</th>
                    <th>รายละเอียดสินค้า</th>
                    <th>วันที่สั่ง</th>
                    <th>เลขที่สั่ง</th>
                    <th>วันที่กำหนดส่ง</th>
                    <th>จำนวน</th>
                    <th>ราคา/หน่วย</th>
                    <th>ราคารวม</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($orders) && count($orders) > 0)
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->customer_code }} - {{ $order->customer_name }}</td>
                    <td>{{ $order->product_code }} - {{ $order->product_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->ord_date)->format('Y-m-d') }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}</td>
                    <td>{{ number_format($order->amount, 2) }}</td>
                    <td>{{ number_format($order->unit_price, 2) }}</td>
                    <td>{{ number_format($order->total_price, 2) }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9">ไม่มีข้อมูลในช่วงวันที่ที่เลือก</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                @if(isset($orders) && count($orders) > 0)
                <tr>
                    <td colspan="6" style="text-align: right;">รวมทั้งหมด</td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                    <td></td>
                    <td>{{ number_format($totalPrice, 2) }}</td>
                </tr>
                @endif
            </tfoot>
        </table>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="window.print()" class="btn-print">พิมพ์</button>
            <a href="{{ route('home.index') }}" class="btn-exit">ออก</a>
        </div>
    </div>
</body>

</html>