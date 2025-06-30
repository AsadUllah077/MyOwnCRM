@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">{{ __('Purchase Detail') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('purchases.update', $purchase->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Supplier --}}
                        <div class="form-group mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id"
                                class="form-control sel-2 form-select @error('supplier_id') is-invalid @enderror" disabled="true">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                            <input type="date" name="purchase_date"
                                value="{{ old('purchase_date', $purchase->purchase_date) }}"
                                class="form-control @error('purchase_date') is-invalid @enderror" readonly="true">
                            @error('purchase_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Purchase Number --}}
                        <div class="form-group mb-3">
                            <label>Purchase Number</label>
                            <input type="text" name="purchase_number"
                                value="{{ old('purchase_number', $purchase->purchase_number) }}"
                                readonly class="form-control @error('purchase_number') is-invalid @enderror" readonly="true">
                            @error('purchase_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="form-group mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" readonly="true">{{ old('notes', $purchase->notes) }}</textarea>
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
                                        {{-- <th>
                                            <button type="button" class="btn btn-success btn-sm" id="addRow">
                                                + Add
                                            </button>
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchaseDetails as $index => $item)
                                        <tr>
                                            <td>
                                                <select class="form-control form-select categorySelect" name="items[{{ $index }}][category_id]" disabled="true">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old("items.$index.category_id", $item->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="items[{{ $index }}][product_id]" class="form-control form-select sel-2 productSelect" disabled="true">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        @if ($product->category_id == old("items.$index.category_id", $item->category_id))
                                                            <option value="{{ $product->id }}"
                                                                {{ old("items.$index.product_id", $item->product_id) == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="items[{{ $index }}][price]"
                                                    value="{{ old("items.$index.price", $item->price) }}"
                                                    class="form-control priceInput" readonly="true">
                                            </td>
                                            <td>
                                                <input type="number" step="1" name="items[{{ $index }}][quantity]"
                                                    value="{{ old("items.$index.quantity", $item->qty) }}"
                                                    class="form-control qtyInput" readonly="true">
                                            </td>
                                            {{-- <td>
                                                <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paid, Discount, and Total --}}
                        <div class="d-flex flex-column align-items-end justify-content-end gap-3 mb-4">
                            <div class="form-group mb-0">
                                <label>Paid Amount</label>
                                <input type="number" step="0.01" id="paid_amount" name="paid_amount"
                                    value="{{ old('paid_amount', $purchase->paid_amount) }}"
                                    class="form-control @error('paid_amount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('paid_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Discount</label>
                                <input type="number" step="0.01" id="discount" name="discount"
                                    value="{{ old('discount', $purchase->discount) }}"
                                    class="form-control @error('discount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('discount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="totalAmount"
                                    value="{{ old('total_amount', $purchase->total_amount) }}"
                                    class="form-control @error('total_amount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('total_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- <button type="submit" class="btn btn-primary">Update Purchase</button>--}}
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
<script>
    const productsData = @json($products->pluck('price', 'id'));
    const productsByCategory = @json($products->groupBy('category_id'));
    let rowIdx = {{ $purchase->purchaseDetails->count() }};

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

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    $(document).on('change', '.productSelect', function() {
        const productId = $(this).val();
        const row = $(this).closest('tr');
        const price = productsData[productId] ?? 0;
        row.find('.priceInput').val(price);
        calculateTotal();
    });

    $(document).on('input', '.priceInput, .qtyInput', function() {
        calculateTotal();
    });

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

    $(document).on('input', '#discount', function() {
        calculateTotal();
    });

    $(document).on('input', '#paid_amount', function() {
        const total_amount = parseFloat($('#totalAmount').val()) || 0;
        let paid_amount = parseFloat($(this).val()) || 0;
        if (paid_amount < 0) paid_amount = 0;
        if (paid_amount > total_amount) {
            paid_amount = total_amount;
            $(this).val(paid_amount.toFixed(2));
        }
    });

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

    $(document).ready(function() {
        calculateTotal();
    });
</script>
@endpush --}}
