@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Categories') }}</span>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Create category</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @auth
                                            @if (auth()->user()->roles->contains('name', 'user'))
                                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($categories->isEmpty())
                        <p class="text-center mt-3">No categories found.</p>
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
        $('#categoriesTable').DataTable({
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
