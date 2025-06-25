@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">{{ __('Edit User') }}</div>

            <div class="card-body">

                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group mb-3">
                        <label>Password <small>(Leave blank to keep current password)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    {{-- Roles --}}
                    <div class="form-group mb-3">
                        <label>Assign Roles</label>
                        <div>
                            @foreach($roles as $role)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        {{ (is_array(old('roles', $userRoleIds)) && in_array($role->id, old('roles', $userRoleIds))) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
