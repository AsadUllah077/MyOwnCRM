@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">{{ __('Create Customer') }}</div>

            <div class="card-body">

                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Number --}}
                    <div class="form-group mb-3">
                        <label>Number</label>
                        <input type="text" name="number" class="form-control @error('number') is-invalid @enderror">
                        @error('number')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="form-group mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                    </div>



                    <button type="submit" class="btn btn-primary">Create Customer</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
