@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">{{ __('Update Customer') }}</div>

            <div class="card-body">

                <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name"
                            value="{{ old('name', $customer->name) }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="{{ old('email', $customer->email) }}"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Number --}}
                    <div class="form-group mb-3">
                        <label>Number</label>
                        <input type="text" name="number"
                            value="{{ old('number', $customer->number) }}"
                            class="form-control @error('number') is-invalid @enderror">
                        @error('number')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="form-group mb-3">
                        <label>Address</label>
                        <textarea name="address"
                            class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update customer</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
