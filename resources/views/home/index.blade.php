@extends('layouts.app')

@section('content')
    hello

    <form action="{{ route('logout') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary">Logout</button>
    </form>
@endsection