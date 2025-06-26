@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">{{ __('Create Category') }}</div>

            <div class="card-body">

                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create Category</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
