<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #000;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: 600;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .header .subtitle {
            font-size: 9pt;
            color: #555;
            margin-bottom: 8px;
        }

        .header .range {
            font-size: 9pt;
            font-weight: 500;
            margin-top: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            page-break-inside: avoid;
        }

        th {
            background-color: #f8f8f8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 8.5pt;
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }

        tfoot td {
            font-weight: 600;
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #777;
            text-align: center;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>LAPORAN TRANSAKSI</h2>
            <div class="range">
                Periode:
                <strong>
                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Semua Periode' }}
                    s/d
                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Sekarang' }}
                </strong>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Pelanggan</th>
                    <th style="width: 25%;">Tanggal Transaksi</th>
                    <th style="width: 20%; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach ($reports as $index => $report)
                    @php $grandTotal += $report->total; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }}</td>
                        <td class="text-right">Rp{{ number_format($report->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total Keseluruhan:</td>
                    <td class="text-right">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }} | Halaman <span class="page-number"></span>
        </div>
    </div>
</body>

</html>
