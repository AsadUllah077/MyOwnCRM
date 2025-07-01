@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Sales') }}</span>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">Create Sale</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="saleTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Sale Number</th>
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
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->sale_number }}</td>
                                            <td>{{ $sale->sale_date }}</td>
                                            <td>{{ number_format($sale->total_amount + $sale->discount, 2) }}</td>

                                            <td>{{ number_format($sale->discount, 2) }}</td>
                                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                                            <td>{{ number_format($sale->paid_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $sale->payment_status == 'paid'
                                                        ? 'success'
                                                        : ($sale->payment_status == 'partial'
                                                            ? 'warning'
                                                            : 'secondary') }}">
                                                    {{ ucfirst($sale->payment_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('sales.show', $sale->id) }}"
                                                    class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('sales.edit', $sale->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('sales.destroy', $sale->id) }}"
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

                        @if ($sales->isEmpty())
                            <p class="text-center mt-3">No sales found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    $@push('scripts')
        <script>
            $(document).ready(function() {
                $('#saleTable').DataTable({
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
