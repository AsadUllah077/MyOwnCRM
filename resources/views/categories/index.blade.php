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
                        <table class="table table-bordered table-striped">
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
                                                    <a href="{{ route('categories.edit', $category->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ route('categories.destroy', $category->id) }}" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this category?');">
                                                        Delete
                                                    </a>
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
