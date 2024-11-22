@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1 class="mb-4">Login</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Errore:</strong> {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Accedi</button>
    </form>
@endsection
