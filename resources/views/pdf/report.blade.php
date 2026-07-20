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
            margin-bottom: 20px;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary p {
            margin: 5px 0;
            font-weight: bold;
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
    </style>
</head>

<body>

    <h2>Financial Transaction Report</h2>

    <div class="summary">
        <p>Total Income: <span class="text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
        <p>Total Expense: <span class="text-red">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span></p>
        <p>Current Balance: Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Description</th>
                <th>Type</th>
                <th>Amount (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>
                    @if($transaction->type === 'income')
                    <span class="text-green">Income</span>
                    @else
                    <span class="text-red">Expense</span>
                    @endif
                </td>
                <td>{{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>