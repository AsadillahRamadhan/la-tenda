<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
        }

        body {
            width: 72mm;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .table {
            width: 100%;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 2px 0;
        }

        .table td:last-child,
        .table th:last-child {
            text-align: right;
        }

        .total {
            font-weight: bold;
            margin-top: 5px;
        }

        body {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="bold">La Tenda Spaghetti</div>
        <div>jl. Ciliwung No. 5H</div>
        <div>Purwantoro, Kec. Blimbing</div>
        <div>Kota Malang</div>
    </div>

    <div class="separator"></div>

    <table class="table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="idr">{{ $item->product->price }}</td>
                    <td class="idr">{{ $item->quantity * $item->product->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>

    <table class="table">
        <tr>
            <td class="bold">Payment Method</td>
            <td></td>
            <td></td>
            <td class="bold">{{ $transaction->payment_method }}</td>
        </tr>
        <tr>
            <td class="bold">Price</td>
            <td></td>
            <td></td>
            <td class="bold idr">{{ $transaction->price }}</td>
        </tr>
        <tr>
            <td class="bold">Tax</td>
            <td></td>
            <td></td>
            <td class="bold idr">{{ $transaction->tax }}</td>
        </tr>
        <tr>
            <td class="bold">Total Price</td>
            <td></td>
            <td></td>
            <td class="bold idr">{{ $transaction->total_price }}</td>
        </tr>
    </table>

    <div class="separator"></div>

    <div class="text-center">
        <div>Terima kasih!</div>
        <div>Selamat menikmati :)</div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.idr').forEach((ctn) => {
            const number = parseInt(ctn.innerHTML);
            ctn.innerHTML = number.toLocaleString('id-ID');
        });

        window.print();

        window.onafterprint = () => {
            window.location.href = "{{ route('transaction') }}"
        }
    });
</script>

</html>
