@extends('master')
@section('content')
<?php
use Illuminate\Support\Facades\Session;
$user = Session::get('user'); 
?>

{{ View::make('nav') }}


<h2>Book Now</h2>

<img src="images/bike02.png" alt="Product Image" style="height:350px">
<form action="/booking" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="{{ $user['name'] }}">
    @error('name')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="phone">Phone:</label>
    <input type="number" id="phone" name="phone">
    @error('phone')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="{{ $user['email'] }}">
    @error('email')
    <p style="color: red;">{{ $message }}</p>
    @enderror

    <label for="image">Upload Image:</label>
    <input type="file" id="image" name="image" accept="image/*">

    <input type="hidden" name='price' value="40000">
    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
    <input type="submit" value="BOOK">
</form>
@endsection
