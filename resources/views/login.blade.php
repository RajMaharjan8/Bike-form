@extends('master')
@section('content')
    <h3>LOGIN</h3>
    <div class="container custom-login">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        @error('email')
                        <p style="color: red;">{{ $message }}</p>
                        @enderror
                        
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                        @error('password')
                        <p style="color: red;">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>

        <a href="/register">Register</a>

    </div> 
@endsection