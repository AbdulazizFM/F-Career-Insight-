<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment #{{ $payment->payment_id }} - Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f7fb; color: #0f172a; }
        .print-shell { max-width: 760px; margin: 26px auto; }
        .paper { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 22px; }
        .logo { width: 72px; height: 72px; object-fit: contain; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px; background: #fff; }
        .meta-table td { padding: 7px 0; border-bottom: 1px dashed #e5eaf4; }
        .meta-label { color: #64748b; width: 38%; }
        .tools { display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px; }
        @media print {
            body { background: #fff; }
            .tools { display: none !important; }
            .print-shell { max-width: none; margin: 0; }
            .paper { border: 0; padding: 0; }
        }
    </style>
</head>
<body>
@php
    $logoPath = file_exists(public_path('logo-report.png')) ? asset('logo-report.png') : asset('logo.png');
@endphp
<div class="print-shell">
    <div class="tools">
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
    </div>

    <div class="paper">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $logoPath }}" alt="Career Insights Logo" class="logo">
                <div>
                    <h5 class="mb-1">Payment Statement</h5>
                    <div class="small text-muted">Career Insights Administration</div>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-semibold">Payment #{{ $payment->payment_id }}</div>
                <div class="small text-muted">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i') : '-' }}</div>
            </div>
        </div>

        <table class="w-100 meta-table">
            <tr><td class="meta-label">Transaction ID</td><td class="fw-semibold">{{ $payment->transaction_id ?: '-' }}</td></tr>
            <tr><td class="meta-label">Amount</td><td class="fw-semibold">{{ number_format((float)$payment->amount, 2) }} SAR</td></tr>
            <tr><td class="meta-label">Payment Method</td><td class="fw-semibold">{{ $payment->payment_method ?: '-' }}</td></tr>
            <tr><td class="meta-label">Status</td><td class="fw-semibold">{{ $payment->status ?: '-' }}</td></tr>
            <tr><td class="meta-label">Subscription ID</td><td class="fw-semibold">{{ $payment->subscription_id ?: '-' }}</td></tr>
            <tr><td class="meta-label">Purchase ID</td><td class="fw-semibold">{{ $payment->purchase_id ?: '-' }}</td></tr>
        </table>
    </div>
</div>
</body>
</html>
