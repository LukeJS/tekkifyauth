@extends('layouts.app')

@section('content')
    @include('common.errors')

    <form action="{{ route('register') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="registerUsername">Username</label>
            <input type="text" name="username" class="form-control" id="registerUsername" aria-describedby="registerUsernameHelp" placeholder="Username">
            <small id="registerUsernameHelp" class="form-text text-muted">This does not need to be the same as your Minecraft username.</small>
        </div>
        <div class="form-group">
            <label for="registerEmail">Email address</label>
            <input type="email" name="email" class="form-control" id="registerEmail" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" name="password" class="form-control" id="registerPassword" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="registerPasswordConfirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="registerPasswordConfirmation" placeholder="Confirm Password">
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
@endsection