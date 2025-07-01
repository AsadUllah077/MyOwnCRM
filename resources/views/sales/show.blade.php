@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">{{ __('Sale Detail') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sales.update', $sale->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Supplier --}}
                        <div class="form-group mb-3">
                            <label>Customer</label>
                            <select name="supplier_id"
                                class="form-control sel-2 form-select @error('supplier_id') is-invalid @enderror" disabled="true">
                                <option value="">Select Supplier</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- sale Date --}}
                        <div class="form-group mb-3">
                            <label>sale Date</label>
                            <input type="date" name="sale_date"
                                value="{{ old('sale_date', $sale->sale_date) }}"
                                class="form-control @error('sale_date') is-invalid @enderror" readonly="true">
                            @error('sale_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- sale Number --}}
                        <div class="form-group mb-3">
                            <label>sale Number</label>
                            <input type="text" name="sale_number"
                                value="{{ old('sale_number', $sale->sale_number) }}"
                                readonly class="form-control @error('sale_number') is-invalid @enderror" readonly="true">
                            @error('sale_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="form-group mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" readonly="true">{{ old('notes', $sale->notes) }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- sale Items --}}
                        <div class="form-group mb-4">
                            <label>Sale Items</label>
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
                                    @foreach ($sale->saleDetails as $index => $item)
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
                                    value="{{ old('paid_amount', $sale->paid_amount) }}"
                                    class="form-control @error('paid_amount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('paid_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Discount</label>
                                <input type="number" step="0.01" id="discount" name="discount"
                                    value="{{ old('discount', $sale->discount) }}"
                                    class="form-control @error('discount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('discount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label>Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="totalAmount"
                                    value="{{ old('total_amount', $sale->total_amount) }}"
                                    class="form-control @error('total_amount') is-invalid @enderror" style="width: 200px;" readonly="true">
                                @error('total_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- <button type="submit" class="btn btn-primary">Update sale</button>--}}
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

