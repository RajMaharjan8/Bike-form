@extends('master')
@section('content')
    <div class="container">
        <h3 class="mt-5 mb-4 text-center">Register</h3>
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" name="name" class="form-control" id="name">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="role" class="form-label">Role:</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="1">Admin</option>
                                    <option value="2">Client</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address:</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password:</label>
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

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password:</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="btn btn-link">Already have an account? Login</a>
        </div>
    </div>
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
