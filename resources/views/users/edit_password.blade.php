@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Breadcrumb list --}}
            <div class="mb-4">
                <a href="{{ route('home') }}" class="text-body"><i class="fas fa-home"></i></a>
                <i class="fas fa-caret-right"></i>
                <a href="{{ route('user.show', $user->id) }}" class="text-body">{{ $user->name }}'s profile</a>
                <i class="fas fa-caret-right"></i>
                <a href="#" class="text-body">Edit profile</a>
                <i class="fas fa-caret-right"></i>
                <a href="#" class="text-body">Edit password</a>
            </div>

            <h1>Edit password</h1>
            <form action="{{ route('user.updatePassword', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Current password</label>
                    <input type="password" class="form-control {{ $errors->has('current_password')?'is-invalid':'' }}" name="current_password">
                    
                    @if($errors->has('current_password'))
                        <p class="text-danger">{{ $errors->first('current_password') }}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>New password</label>
                    <input type="password" class="form-control {{ $errors->has('new_password')?'is-invalid':'' }}" name="new_password">
                    
                    @if($errors->has('new_password'))
                        <p class="text-danger">{{ $errors->first('new_password') }}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>New password confirmation</label>
                    <input type="password" class="form-control" name="new_password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection