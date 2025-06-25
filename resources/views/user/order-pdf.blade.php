<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #e2e2e2;
            margin-bottom: 10px;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .product-img {
            height: 60px;
            object-fit: cover;
        }

        .summary {
            width: 100%;
            margin-top: 0;
        }

        .summary p {
            display: flex;
            justify-content: space-between;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .left-column {
            width: 58%;
            float: left;
        }

        .right-column {
            width: 40%;
            float: right;
        }

        .payment-proof-img {
            width: 120px;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }

        .row-label {
            font-weight: bold;
            width: 100px;
            display: inline-block;
        }

        .inline-block {
            display: inline-block;
            vertical-align: top;
        }
    </style>
</head>

<body>

    <h1>Order #{{ $order->order_number }}</h1>

    <div class="section clearfix">
        <div class="left-column">
            <div class="section-title">Order Information</div>
            <p><span class="row-label">Status:</span> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
            <p><span class="row-label">Date:</span> {{ $order->created_at->format('F j, Y, H:i') }}</p>

            <div class="section-title" style="margin-top: 20px;">Customer Information</div>
            <p><span class="row-label">Name:</span> {{ $order->user->name }}</p>
            <p><span class="row-label">Email:</span> {{ $order->user->email }}</p>
            <p><span class="row-label">Phone:</span> {{ $order->user->phone }}</p>
            <p><span class="row-label">Address:</span> {{ $order->user->address }}</p>
        </div>

        @if ($order->paymentProof)
            <div class="right-column">
                <div class="section-title">Payment Information</div>
                <p><span class="row-label">Bank:</span> {{ $order->paymentProof->paymentMethod->bank }}</p>
                <p><span class="row-label">No. Account:</span> {{ $order->paymentProof->paymentMethod->no_rekening }}
                </p>
                <p><span class="row-label">Name:</span> {{ $order->paymentProof->paymentMethod->nama }}</p>
                <p><span class="row-label">Status:</span> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                <p><span class="row-label">Proof:</span></p>
                <img src="{{ public_path('storage/' . $order->paymentProof->image) }}" alt="Payment Proof"
                    class="payment-proof-img">
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->nama_produk }}</td>
                        <td>
                            <img src="{{ public_path('storage/' . $item->product->gambar) }}" class="product-img"
                                alt="Product Image">
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section clearfix">
        <div class="left-column">
            @if ($order->paymentProof->notes)
                <div class="section">
                    <div class="section-title">Notes</div>
                    <p>{{ $order->paymentProof->notes }}</p>
                </div>
            @endif
        </div>

        <div class="right-column">
            <div class="section">
                <div class="section-title">Order Summary</div>
                <div class="summary">
                    <p><strong>Subtotal:</strong> <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span></p>
                    @if ($order->voucher_code)
                        <p><strong>Voucher ({{ $order->voucher_code }}):</strong>
                            <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </p>
                    @endif
                    <p><strong>Total:</strong>
                        <span><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
