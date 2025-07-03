@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Sale Ledger') }}</span>
                        {{-- <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">Create Sale</a> --}}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="saleledgerTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sale Number</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Pending Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saleledgers as $sale)
                                        <tr>
                                            <td>{{ $loop->iteration ?? 0 }}</td>
                                            <td>{{ $sale->sale_number ?? 'N/A' }}</td>
                                            <td>{{ optional($sale->created_at)->format('Y-m-d') ?? 'N/A' }}</td>
                                            <td>{{ number_format($sale->total_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($sale->paid_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($sale->pending_amount ?? 0, 2) }}</td>
                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
