{{-- @extends('master')
@section('content')
    <h3>LOGIN</h3>
    <div class="container custom-login">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="{{route('login.post')}}" method="POST">
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

        <a href="{{ route('register') }}">Register</a>

    </div> 
@endsection --}}

@extends('master')
@section('content')
    <div class="container">
        <h3 class="mt-5 mb-4 text-center">LOGIN</h3>
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        @error('email')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('exampleInputPassword1')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}" class="btn btn-link">Don't have an account? Register</a>
        </div>
    </div>

    <!-- Add this JavaScript section -->
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>

    <!-- Include Bootstrap CSS and Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
