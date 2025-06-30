@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">{{ __('Create Purchase') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('purchases.store') }}">
                        @csrf

                        {{-- Supplier --}}
                        <div class="form-group mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id"
                                class="form-control sel-2 form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Purchase Date --}}
                        <div class="form-group mb-3">
                            <label>Purchase Date</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                                class="form-control @error('purchase_date') is-invalid @enderror">
                            @error('purchase_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Purchase Number --}}
                        <div class="form-group mb-3">
                            <label>Purchase Number</label>
                            <input type="text" name="purchase_number" readonly value="{{ old('purchase_number',$pur_num) }}"
                                class="form-control @error('purchase_number') is-invalid @enderror">
                            @error('purchase_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="form-group mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Purchase Items --}}
                        <div class="form-group mb-4">
                            <label>Purchase Items</label>
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>
                                            <button type="button" class="btn btn-success btn-sm" id="addRow">
                                                + Add
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Empty, rows will be dynamically added --}}
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column align-items-end justify-content-end gap-3 mb-4">
                            <div class="form-group mb-0">
                                <label>Paid Amount</label>
                                <input type="number" step="0.01" id="paid_amount" name="paid_amount"
                                    value="{{ old('paid_amount') }}"
                                    class="form-control @error('paid_amount') is-invalid @enderror" style="width: 200px;">
                                @error('paid_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Discount</label>
                                <input type="number" step="0.01" id="discount" name="discount"
                                    value="{{ old('discount') }}"
                                    class="form-control @error('discount') is-invalid @enderror" style="width: 200px;">
                                @error('discount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="totalAmount"
                                    class="form-control @error('total_amount') is-invalid @enderror" style="width: 200px;"
                                    readonly>
                                @error('total_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Purchase</button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const productsData = @json($products->pluck('price', 'id'));
    const productsByCategory = @json($products->groupBy('category_id'));
</script>

<script>
    let rowIdx = 1;

    // Add row handler
    $('#addRow').on('click', function() {
        const newRow = `
        <tr>
            <td>
                <select class="form-control form-select categorySelect" name="items[${rowIdx}][category_id]">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[${rowIdx}][product_id]" class="form-control form-select sel-2 productSelect">
                    <option value="">Select Product</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIdx}][price]" class="form-control priceInput">
            </td>
            <td>
                <input type="number" step="1" name="items[${rowIdx}][quantity]" class="form-control qtyInput">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
            </td>
        </tr>`;
        $('#itemsTable tbody').append(newRow);
        rowIdx++;
    });

    // Remove row handler
    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    // When product is selected → autofill price
    $(document).on('change', '.productSelect', function() {
        const productId = $(this).val();
        const row = $(this).closest('tr');
        const price = productsData[productId] ?? 0;
        row.find('.priceInput').val(price);
        calculateTotal();
    });

    // Update total on price or quantity change
    $(document).on('input', '.priceInput, .qtyInput', function() {
        calculateTotal();
    });

    // When a category is selected in a row → update products dropdown in same row
    $(document).on('change', '.categorySelect', function() {
        const categoryId = $(this).val();
        const row = $(this).closest('tr');
        const productSelect = row.find('.productSelect');

        productSelect.empty().append('<option value="">Select Product</option>');

        if (categoryId && productsByCategory[categoryId]) {
            productsByCategory[categoryId].forEach(product => {
                productSelect.append(`<option value="${product.id}">${product.name}</option>`);
            });
        }
    });

    // Discount change handler
    $(document).on('input', '#discount', function() {
        calculateTotal();
    });

    // Paid amount clamp
    $(document).on('input', '#paid_amount', function() {
        const total_amount = parseFloat($('#totalAmount').val()) || 0;
        let paid_amount = parseFloat($(this).val()) || 0;

        if (paid_amount < 0) paid_amount = 0;
        if (paid_amount > total_amount) {
            paid_amount = total_amount;
            $(this).val(paid_amount.toFixed(2));
        }
    });

    // Calculate total amount
    function calculateTotal() {
        let subtotal = 0;
        $('#itemsTable tbody tr').each(function() {
            const price = parseFloat($(this).find('.priceInput').val()) || 0;
            const qty = parseInt($(this).find('.qtyInput').val()) || 0;
            subtotal += price * qty;
        });

        let discount = parseFloat($('#discount').val()) || 0;
        if (discount < 0) discount = 0;

        let total = subtotal - discount;
        if (total < 0) total = 0;

        $('#totalAmount').val(total.toFixed(2));
    }
</script>
@endpush
