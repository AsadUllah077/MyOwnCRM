@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Report Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6>Total Purchases</h6>
                            <p class="font-weight-bold">{{ $purchaseCount }}</p>
                            <h6>Total Purchase Amount</h6>
                            <p class="text-success font-weight-bold">{{ number_format($purchaseTotalAmount, 2) }}</p>
                            <h6>Total Purchase Paid</h6>
                            <p class="text-primary font-weight-bold">{{ number_format($purchasePaidAmount, 2) }}</p>
                            <h6>Total Purchase Pending</h6>
                            <p class="text-danger font-weight-bold">{{ number_format($purchasePendingAmount, 2) }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6>Total Sales</h6>
                            <p class="font-weight-bold">{{ $saleCount }}</p>
                            <h6>Total Sales Amount</h6>
                            <p class="text-success font-weight-bold">{{ number_format($saleTotalAmount, 2) }}</p>
                            <h6>Total Sales Paid</h6>
                            <p class="text-primary font-weight-bold">{{ number_format($salePaidAmount, 2) }}</p>
                            <h6>Total Sales Pending</h6>
                            <p class="text-danger font-weight-bold">{{ number_format($salePendingAmount, 2) }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <h6>Net Totals (Sales - Purchases)</h6>
                            @php
                                $netTotal = $saleTotalAmount - $purchaseTotalAmount;
                                $netPaid = $salePaidAmount - $purchasePaidAmount;
                                $netPending = $salePendingAmount - $purchasePendingAmount;
                            @endphp
                            <h6>Total</h6>
                            <p class="font-weight-bold {{ $netTotal >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netTotal, 2) }}
                            </p>
                            <h6>Paid</h6>
                            <p class="font-weight-bold {{ $netPaid >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netPaid, 2) }}
                            </p>
                            <h6>Pending</h6>
                            <p class="font-weight-bold {{ $netPending >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netPending, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- The rest of your report table follows --}}
</div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#saleledgerTable').DataTable({
                    responsive: true,
                    language: {
                        search: "Search sales:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ sales",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "→",
                            previous: "←"
                        },
                    }
                });
            });
        </script>
    @endpush
@endsection
