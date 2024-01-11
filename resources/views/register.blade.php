@extends('master')
@section('content')
    <h3>Register</h3>
    <div class="container custom-login">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="/register" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name='name' class="form-control" id="name">
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role">
                                  <option value="1">Admin</option>
                                  <option value="2">Client</option>
                              
                                </select>
                            </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                    </div>

            
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>

        </div>

        <a href="/login">Login</a>
    </div> 
@endsection