@extends('layout/layout')

@section('content')

    <h1>Reset Password</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color: red">{{ $error }}</p>
        @endforeach
    @endif

    <form action="{{ route('resetPassword') }}" method="POST">
        @csrf

        <input type="hidden" name="id" value="{{ $user[0]['id'] }}">

        <input type="password" name="password" placeholder="Enter the password">
        <br><br>

        <input type = "password" name="password_confirmation" placeholder="confirm the password">
        <br><br>

        <input type="submit" value="Reset password">
    </form>

    <a href="/">Reset</a>



@endsection
