@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Customers') }}</span>
                        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Create Customer</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="customersTable">
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
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->number }}</td>
                                        <td>{{ $customer->address }}</td>

                                        <td>
                                            @auth
                                                @if (auth()->user()->roles->contains('name', 'admin'))
                                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ route('customers.destroy', $customer->id) }}" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this customer?');">
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

                        @if ($customers->isEmpty())
                            <p class="text-center mt-3">No customers found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    $@push('scripts')
        <script>
            $(document).ready(function() {
                $('#customersTable').DataTable({
                    responsive: true,
                    language: {
                        search: "Search customers:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ customers",
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
