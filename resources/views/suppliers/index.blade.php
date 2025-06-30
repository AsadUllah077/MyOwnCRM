@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Suppliers') }}</span>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">Create Supplier</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="suppliersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->number }}</td>
                                        <td>{{ $supplier->address }}</td>

                                        <td>
                                            @auth
                                                @if (auth()->user()->roles->contains('name', 'admin'))
                                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ route('suppliers.destroy', $supplier->id) }}" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this supplier?');">
                                                        Delete
                                                    </a>
                                                @endif
                                            @endauth

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        @if ($suppliers->isEmpty())
                            <p class="text-center mt-3">No suppliers found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    $@push('scripts')
        <script>
            $(document).ready(function() {
                $('#suppliersTable').DataTable({
                    responsive: true,
                    language: {
                        search: "Search suppliers:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ suppliers",
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
