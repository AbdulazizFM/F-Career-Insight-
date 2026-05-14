@extends('layouts.admin')

@section('content')
    <x-page-header
        title="Payments"
        subtitle="Review transactions and verify or reject payment records"
        :breadcrumbs="[
            ['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Payments']
        ]"
    />

    <div class="panel-card">
        <div class="panel-body p-0">
            <x-data-table id="adminPaymentsTable" class="table-striped" :exportable="true">
                <thead class="table-light">
                    <tr>
                        <th>Transaction</th>
                        <th>Subscription</th>
                        <th>Purchase</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->transaction_id }}</td>
                            <td>{{ optional($payment->subscription)->plan_type ?? '-' }}</td>
                            <td>{{ optional(optional($payment->jobPurchase)->subMajor)->sub_major_name ?? '-' }}</td>
                            <td>{{ $payment->amount }} SAR</td>
                            <td>{{ $payment->status }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal{{ $payment->payment_id }}" title="Verify payment">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <x-confirm-modal
                                        :id="'verifyPaymentModal' . $payment->payment_id"
                                        title="Verify Payment?"
                                        body="This will mark the transaction as completed."
                                        action-label="Verify"
                                        action-color="success"
                                        icon="bi-check-circle"
                                        icon-color="success"
                                        :form-action="route('admin.payments.verify', $payment->payment_id)"
                                        form-method="POST"
                                    />

                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal{{ $payment->payment_id }}" title="Reject payment">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <x-confirm-modal
                                        :id="'rejectPaymentModal' . $payment->payment_id"
                                        title="Reject Payment?"
                                        body="The payment status will be changed to rejected."
                                        action-label="Reject"
                                        action-color="danger"
                                        icon="bi-exclamation-triangle"
                                        icon-color="danger"
                                        :form-action="route('admin.payments.reject', $payment->payment_id)"
                                        form-method="POST"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-empty-state icon="bi-credit-card" title="No payments found" message="No payment records are available yet." /></td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </div>
    </div>
@endsection
