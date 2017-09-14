@extends('layouts.app')

@section('content')
    @include('common.errors')

    <form action="{{ route('login') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="loginUsername">Username</label>
            <input type="text" name="username" class="form-control" id="loginUsername" aria-describedby="loginUsernameHelp" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="loginPassword">Password</label>
            <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">login</button>
    </form>
@endsection