@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Purchase Ledger') }}</span>
                        {{-- <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">Create Purchase</a> --}}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="purchaseledgerTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Purchase Number</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Pending Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $purchaseledgers as $purchase)


                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $purchase->purchase_number ?? 'N/A' }}</td>
                                            <td>{{ optional($purchase->created_at)->format('Y-m-d') ?? 'N/A' }}</td>
                                            <td>{{ number_format($purchase->total_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($purchase->paid_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($purchase->pending_amount ?? 0, 2) }}</td>
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
                $('#purchaseledgerTable').DataTable({
                    responsive: true,
                    language: {
                        search: "Search purchases:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ purchases",
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
