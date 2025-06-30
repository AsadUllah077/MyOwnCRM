@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Purchase') }}</span>
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">Create Purchase</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="purchaseTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier</th>
                                        <th>Purchase Number</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Discount Amount</th>
                                        <th>Total After Discount</th>
                                        <th>Paid Amount</th>
                                        <th>Payment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                            <td>{{ $purchase->purchase_number }}</td>
                                            <td>{{ $purchase->purchase_date }}</td>
                                            <td>{{ number_format($purchase->total_amount + $purchase->discount, 2) }}</td>

                                            <td>{{ number_format($purchase->discount, 2) }}</td>
                                            <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                            <td>{{ number_format($purchase->paid_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $purchase->payment_status == 'paid'
                                                        ? 'success'
                                                        : ($purchase->payment_status == 'partial'
                                                            ? 'warning'
                                                            : 'secondary') }}">
                                                    {{ ucfirst($purchase->payment_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('purchases.show', $purchase->id) }}"
                                                    class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                                        class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($purchases->isEmpty())
                            <p class="text-center mt-3">No purchases found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    $@push('scripts')
        <script>
            $(document).ready(function() {
                $('#purchaseTable').DataTable({
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
