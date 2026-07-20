<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
        }

        .period {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary p {
            margin: 5px 0;
            font-weight: bold;
        }

        .summary .net-worth {
            font-size: 16px;
            color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-green {
            color: green;
        }

        .text-red {
            color: red;
        }

        .text-yellow {
            color: #b45309;
        }

        .section-title {
            font-size: 15px;
            margin-top: 24px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }
    </style>
</head>

<body>

    <h2>Financial Transaction Report</h2>
    <p class="period">
        Periode: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
        &mdash; Dicetak: {{ now()->translatedFormat('d M Y H:i') }}
    </p>

    <div class="summary">
        <p class="net-worth">Net Worth (Semua Akun): Rp {{ number_format($netWorth, 0, ',', '.') }}</p>
        <p>Total Income (bulan ini): <span class="text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
        <p>Total Expense (bulan ini): <span class="text-red">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span></p>
        <p>Selisih Bulan Ini: Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>

    @if($budgetAlerts->isNotEmpty())
    <div class="section-title">Peringatan Budget</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Persentase Terpakai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgetAlerts as $alert)
            <tr>
                <td>{{ $alert->category->name }}</td>
                <td class="{{ $alert->percentage >= 100 ? 'text-red' : 'text-yellow' }}">
                    {{ $alert->percentage }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">Rincian Transaksi</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Description</th>
                <th>Akun</th>
                <th>Kategori</th>
                <th>Type</th>
                <th>Amount (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->account->name ?? '—' }}</td>
                <td>{{ $transaction->category->name ?? ($transaction->transfer_group_id ? 'Transfer' : '—') }}</td>
                <td>
                    @if($transaction->type === 'income')
                    <span class="text-green">Income</span>
                    @else
                    <span class="text-red">Expense</span>
                    @endif
                </td>
                <td>{{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #888;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>