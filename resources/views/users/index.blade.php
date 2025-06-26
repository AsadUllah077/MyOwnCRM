@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Users') }}</span>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Create User</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{ implode(', ', $user->roles->pluck('name')->toArray()) }}
                                        </td>
                                        <td>
                                            @auth
                                                @if (auth()->user()->roles->contains('name', 'admin'))
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">
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

                        @if ($users->isEmpty())
                            <p class="text-center mt-3">No users found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    $@push('scripts')
        <script>
            $(document).ready(function() {
                $('#usersTable').DataTable({
                    responsive: true,
                    language: {
                        search: "Search Users:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ users",
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
