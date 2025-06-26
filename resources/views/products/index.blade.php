@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Products') }}</span>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Create Product</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="productTable">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                                    width="80" height="80">
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            @auth
                                                @if (auth()->user()->roles->contains('name', 'user'))
                                                    <a href="{{ route('products.edit', $product->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ route('products.destroy', $product->id) }}"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this product?');">
                                                        Delete
                                                    </a>
                                                @endif
                                            @endauth

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($products->isEmpty())
                            <p class="text-center mt-3">No products found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#productTable').DataTable({
            responsive: true,
            language: {
                search: "Search Categories:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ categories",
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
